<?php
$lang = '/<lang:(|en|de|ru)>';
return [
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'ruleConfig' => [
        'class' => 'app\components\LanguageUrlRule'
    ],
    'rules' => [
        '/<bank:[^/]+>/<currency:[^/]+>/<date:\d{4}-\d{2}-\d{2}_\d{2}\d{2}\d{2}>/' => 'site/course',
        "/" => 'site/index',
    ],
];