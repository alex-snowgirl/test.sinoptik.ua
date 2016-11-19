<?php

namespace app\controllers;

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
        $courses = Course::find()
            ->orderBy(['pub_date' => SORT_DESC])
            ->limit(10)->all();

        $userCourses = [];

        return $this->render('index', [
            'courses' => $courses,
            'user_courses' => $userCourses,
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

        $bank = Bank::findOne(['code' => $request->get('bank')]);
        $currency = Currency::findOne(['code' => $request->get('currency')]);
        if (!$bank || !$currency) {
            throw new NotFoundHttpException;
        }

        $dateTime = Course::pubDateFromUrl($request->get('date'));

        $course = Course::findOne(['pub_date' => $dateTime->format('c'), 'bank' => $bank->id, $currency->id]);

        if (!$course) {
            throw new NotFoundHttpException;
        }

        return $this->render('course', [
            'course' => $course,
        ]);
    }
}
