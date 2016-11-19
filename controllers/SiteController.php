<?php

namespace app\controllers;

use app\components\SimpleCache;
use app\models\Bank;
use app\models\Course;
use app\models\Currency;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $request = Yii::$app->getRequest();
        $where = [];

        /*
         * filters order the same as table indexes
         */

        if ($tmp = $request->get('bank_id')) {
            $where[] = ['bank_id' => $tmp];
        }

        if ($tmp = $request->get('currency_id')) {
            $where[] = ['currency_id' => $tmp];
        }

        if ($tmp = $request->get('date_from')) {
            $where[] = ['>=', 'pub_date', $tmp];
        }

        if ($tmp = $request->get('date_to')) {
            $where[] = ['<=', 'pub_date', $tmp];
        }

        $cache = new SimpleCache();

        if ($where) {
            $courses = $cache->call('courses_' . md5(serialize($where)), function () use ($where) {
                $courses = Course::find();

                foreach ($where as $clause) {
                    $courses->andWhere($clause);
                }

                $tmp = $courses->orderBy(['pub_date' => SORT_DESC])
                    ->limit(10)
                    ->all();

                return array_map(function ($course) {
                    /** @var Course $course */
                    return $course->getNiceAttributes();
                }, $tmp);
            });
        } else {
            $courses = $cache->get('last_non_where_courses');
        }

        return $this->render('index', [
            'courses' => $courses,
            'currencies' => $cache->getAllModels(Currency::class),
            'banks' => $cache->getAllModels(Bank::class),
            'request' => $request
        ]);
    }

    /**
     * Displays course page.
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionCourse()
    {
        $request = Yii::$app->getRequest();

        if (!$id = $request->get('id')) {
            throw new NotFoundHttpException;
        }

        $cache = new SimpleCache();

        $course = $cache->call("c{$id}", function () use ($id) {
            if (!$tmp = Course::findOne($id)) {
                return null;
            }

            return $tmp->getNiceAttributes();
        });

        if (!$course) {
            throw new NotFoundHttpException;
        }

        return $this->render('course', [
            'course' => $course,
            'history' => $cache->get("cb{$course['bank_id']}.{$course['currency_id']}")
        ]);
    }
}
