<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',

    ],
    'modules' => [
        'redactor' => 'yii\redactor\RedactorModule',
        'gridview' => [
            'class' => '\kartik\grid\Module',
            // 'downloadAction' => 'gridview/export/download',
            'downloadAction' => 'gridview/export/download',  //change default download action to your own export action.
        ],
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],

];
