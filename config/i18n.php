<?php
return [
    'translations' => [
        'app*' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@app/messages',
            'sourceLanguage' => 'en',
            'fileMap' => [
                'app'       => 'app.php',
            ],
            'on missingTranslation' => ['app\components\TranslationEventHandler', 'handleMissingTranslation']
        ]
    ]
];