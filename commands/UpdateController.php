<?php

namespace app\commands;

use app\components\SimpleCache;
use app\models\Bank;
use app\models\Course;
use app\models\Currency;
use yii\base\Exception;
use yii\console\Controller;
use ZipArchive;

class UpdateController extends Controller
{
    public function actionIndex($source)
    {
        $dirPath = dirname(__DIR__) . '/runtime/';
        $archivePath = $dirPath . 'archive.zip';
        $zip = new ZipArchive;
        file_put_contents($archivePath, file_get_contents($source));
        $res = $zip->open($archivePath);

        if ($res === true) {
            $zip->extractTo($dirPath);
            $zip->close();
            $data = json_decode(file_get_contents($dirPath . '/currency.txt'), true);

            foreach ($data as $bank => $currencies) {
                foreach ($currencies as $currency => $courses) {
                    if (!$bankRecord = Bank::findOne(['code' => $bank])) {
                        $bankRecord = new Bank();
                        $bankRecord->setAttributes([
                            'name' => $bank,
                            'code' => $bank
                        ]);

                        $bankRecord->save();
                    }

                    if (!$currencyRecord = Currency::findOne(['code' => $currency])) {
                        $currencyRecord = new Currency();
                        $currencyRecord->setAttributes([
                            'name' => $currency,
                            'code' => $currency
                        ]);

                        $currencyRecord->save();
                    }

                    $courseRecord = new Course();
                    $courseRecord->setAttributes([
                        'bank_id' => $bankRecord->getAttribute('id'),
                        'currency_id' => $currencyRecord->getAttribute('id'),
                        'sell' => round($courses['sell'], 5),
                        'buy' => round($courses['buy'], 5),
                        'pub_date' => date('Y-m-d H:i:s'),
                    ]);
                    $courseRecord->save();
                }
            }

            //@todo modify and call inside above loop (for db requests reducing)
            $this->doCache();
        } else {
            throw new Exception('doh!', 1);
        }

        return 0;
    }

    /**
     *  Cache strategy is next:
     *  1) Cache whole list of currencies and banks (due to limited amount), each list took 1 cache key
     *  2) Cache most common requested courses list (non-filtered index page)
     *  3) Cache bank-currency history pairs (due to limited amount)
     *
     * @todo alternative strategy
     *  1) Cache lists results (IDs only)
     *  2) Cache each entity separately (currency, bank, course)
     *  3) Use multi set/get to work with cache entities
     */
    protected function doCache()
    {
        $cache = new SimpleCache();

        $cache->rebuild();

        $indexCourses = Course::find()
            ->orderBy(['pub_date' => SORT_DESC])
            ->limit(10)
            ->all();

        $cache
            //pre-cache most common courses request
            ->set('last_non_where_courses', array_map(function ($course) {
                /** @var Course $course */
                return $course->getNiceAttributes();
            }, $indexCourses))
            //pre-cache currencies
            ->setAllModels(Currency::class)
            //pre-cache banks
            ->setAllModels(Bank::class);

        //pre-cache history of bank-currency pairs
        foreach (Currency::find()->all() as $currency) {
            foreach (Bank::find()->all() as $bank) {
                $cache->set("cb{$bank->id}.{$currency->id}", function () use ($currency, $bank) {
                    $tmp = Course::find()
                        /*
                         * filters order the same as table indexes
                         */
                        ->where(['bank_id' => $bank->id, 'currency_id' => $currency->id])
                        ->orderBy(['pub_date' => SORT_DESC])
                        ->all();

                    if (!$tmp) {
                        return array();
                    }

                    return array_map(function ($course) {
                        /** @var Course $course */
                        return $course->getNiceAttributes();
                    }, $tmp);
                });
            }
        }

        //pre-cache index courses entities separately
        foreach ($indexCourses as $indexCourse) {
            $cache->set("c{$indexCourse->id}", $indexCourse->getNiceAttributes());
        }
    }
}
