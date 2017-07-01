<?php
return [
    'timeZone' => 'Asia/Shanghai',
    'language' => 'zh-CN',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [

            ],
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port' => 6379,
            'database' => 0,
        ],
        'formatter' => [
            'dateFormat' => 'd-M-Y',
            'datetimeFormat' => 'd-M-Y H:i:s',
            'timeFormat' => 'H:i:s',
            'locale' => 'de-DE', //your language locale
            'defaultTimeZone' => 'Asia/Shanghai', // time zone
        ],
        'session' => [
            'class' => 'yii\redis\Session',
            'timeout' => 86400 * 10,
//            'savePath' => '@web',
        ],
        'i18n' => [
            'translations' => [
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
//                    'basePath' => '/messages',
                    'fileMap' => [
                        'yii' => 'yii.php',
                    ],
                ],
            ],
        ],
    ],
];
