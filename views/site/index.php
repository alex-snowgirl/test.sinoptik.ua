<?php

/**
 * @var $this yii\web\View
 * @var $user_courses app\models\Course[]
 * @var $courses app\models\Course[]
 */

use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <? foreach ($user_courses as $user_course):?>
            <?= Html::a($user_course->bank->getAttribute('name'), [
                'site/course',
                'bank' => $user_course->bank->code,
                'currency' => $user_course->currency->code,
                'date' => $user_course->pubDateToUrl(),
            ])?>
        <?endforeach;?>

    </div>

    <div class="body-content">
        <table>
        <?foreach ($courses as $course):?>
            <tr>
                <td>
                    <?= Html::a($course->bank->getAttribute('name'), [
                        'site/course',
                        'bank' => $course->bank->code,
                        'currency' => $course->currency->code,
                        'date' => $course->pubDateToUrl(),
                    ])?>
                <td>
                <td><?=$course->currency->name?></td>
                <td><?=$course->sell?></td>
                <td><?=$course->buy?></td>
                <td><?=$course->pub_date?></td>
            <tr>
        <?endforeach;?>
        </table>
    </div>
</div>
