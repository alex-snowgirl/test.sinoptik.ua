<?php

/* @var $this yii\web\View */
/* @var $course array */
/* @var $history Course[] */

use yii\helpers\Html;
use app\models\Course;

$this->title = $course['currency'] . ' / ' . $course['bank'];
$this->registerJsFile('/js/core.js');
$tmp = json_encode($course);
$tmp = <<< JS
    var sinoptik = new app();
    sinoptik.addCourseHistory($tmp);
JS;
$this->registerJs($tmp);
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <table class="table">
        <thead>
        <tr>
            <th><?php echo Yii::t('app', 'Sell') ?></th>
            <th><?php echo Yii::t('app', 'Buy') ?></th>
            <th><?php echo Yii::t('app', 'Date') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($history as $historyCourse) { ?>
            <tr>
                <td><?= $course['sell'] ?></td>
                <td><?= $course['buy'] ?></td>
                <td><?= $course['date'] ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
