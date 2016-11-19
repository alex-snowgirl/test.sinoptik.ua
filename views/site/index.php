<?php

/** @var $this View */
/** @var $courses array */
/** @var $currencies Currency[] */
/** @var $banks Bank[] */
/** @var $request Request */

use yii\web\View;
use yii\helpers\Html;
use app\models\Currency;
use app\models\Bank;
use yii\web\Request;

$this->title = Yii::t('app', 'Courses');
$this->registerJsFile('/js/core.js');
$tmp = <<< JS
    var sinoptik = new app();
    sinoptik.drawCourseHistory('history');
    sinoptik.liveSubmit('filters');
JS;
$this->registerJs($tmp);
?>


<div class="container site-index">
    <h1><?php echo Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-xs-12">
            <form class="form-inline" id="filters">
                <label><?php echo Yii::t('app', 'Date From') ?>
                    <input class="form-control" type="datetime-local" name="date_from"
                           value="<?php echo $request->get('date_from') ?>"/>
                </label>
                <label><?php echo Yii::t('app', 'Date To') ?>
                    <input class="form-control" type="datetime-local" name="date_to"
                           value="<?php echo $request->get('date_to') ?>"/>
                </label>
                <label><?php echo Yii::t('app', 'Currency') ?>
                    <select class="form-control" name="currency_id">
                        <?php foreach ($currencies as $id => $currency) { ?>
                            <option value="<?php echo $id ?>"
                                <?php echo $request->get('currency_id') == $id ? 'selected' : '' ?>>
                                <?php echo $currency['name'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </label>
                <label><?php echo Yii::t('app', 'Bank') ?>
                    <select class="form-control" name="bank_id">
                        <?php foreach ($banks as $id => $bank) { ?>
                            <option
                                value="<?php echo $id ?>"
                                <?php echo $request->get('bank_id') == $id ? 'selected' : '' ?>>
                                <?php echo $bank['name'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </label>
            </form>
        </div>
        <div class="col-xs-6">
            <h2><?php echo Yii::t('app', 'Currency Table') ?></h2>
            <table class="table">
                <thead>
                <tr>
                    <th><?php echo Yii::t('app', 'Bid') ?></th>
                    <th><?php echo Yii::t('app', 'Sell') ?> / <?php echo Yii::t('app', 'Buy') ?></th>
                    <th><?php echo Yii::t('app', 'Date') ?></th>
                </tr>
                </thead>
                <tbody>
                <? foreach ($courses as $course) { ?>
                    <tr>
                        <td><a href="<?php echo $course['uri'] ?>"><?= $course['currency'] ?>
                                / <?php echo $course['bank'] ?></a></td>
                        <td><?= $course['sell'] ?> / <?= $course['buy'] ?></td>
                        <td><?= $course['date'] ?></td>
                    </tr>
                <? } ?>
                </tbody>
            </table>
        </div>
        <div class="col-xs-6">
            <h2><?php echo Yii::t('app', 'Currency View History') ?></h2>
            <table class="table">
                <thead>
                <tr>
                    <th><?php echo Yii::t('app', 'Bid') ?></th>
                    <th><?php echo Yii::t('app', 'Sell') ?> / <?php echo Yii::t('app', 'Buy') ?></th>
                    <th><?php echo Yii::t('app', 'Date') ?></th>
                </tr>
                </thead>
                <tbody id="history">
                <tr>
                    <td colspan="3"><?php echo Yii::t('app', 'Empty List') ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>