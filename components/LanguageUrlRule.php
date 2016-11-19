<?php
/**
 * Created by PhpStorm.
 * User: ec
 * Date: 28.09.16
 * Time: 23:37
 */

namespace app\components;


use Yii;
use yii\web\UrlRule;

class LanguageUrlRule extends UrlRule
{
    public function init()
    {
        if ($this->pattern !== null) {
            $this->pattern = '/<lang:[a-z]{2}>/' . $this->pattern;
        }
        $this->defaults['lang'] = Yii::$app->getRequest()->get('lang', Yii::$app->language);
        parent::init();
    }
}