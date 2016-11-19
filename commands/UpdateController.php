<?php

namespace app\commands;

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
        if ($res === TRUE) {
            $zip->extractTo($dirPath);
            $zip->close();
            $data = json_decode(file_get_contents($dirPath.'/currency.txt'), true);
            foreach ($data as $bank => $currencies) {
                foreach ($currencies as $currency => $courses) {

                    $bankRecord = Bank::findOne(['code' => $bank]);
                    if (!$bankRecord) {
                        $bankRecord = new Bank();
                        $bankRecord->setAttributes([
                            'name' => $bank,
                            'code' => $bank
                        ]);

                        $bankRecord->save();
                    }

                    $currencyRecord = Currency::findOne(['code' => $currency]);
                    if (!$currencyRecord) {
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
                        'sell' => round($courses['sell'],5),
                        'buy' => round($courses['buy'],5),
                        'pub_date' => date('Y-m-d H:i:s'),
                    ]);
                    $courseRecord->save();
                }
            }
        } else {
            throw new Exception('doh!', 1);
        }

        return 0;
    }
}
