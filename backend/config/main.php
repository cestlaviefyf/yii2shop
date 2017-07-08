<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => \backend\models\User::className(),
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'loginUrl' => 'user/login',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
//            'suffix' =>'.html',
            'rules' => [
            ],
        ],
        'qiniu'=>[
            'class' => \backend\components\Qiniu::className(),
            'up_host' => 'http://up.qiniu.com',
            'accessKey'=>'W2IswhjFT1uu-GUzUGxTEazWk08CThbrQwEBfGwZ',
            'secretKey'=>'CT-yMAAQh9FTAOctGW1CHamymsC0_GJ5SvrLE9ZF',
            'bucket'=>'cestlavie',
            'domain'=>'http://or9r8j3lc.bkt.clouddn.com'
        ]
    ],
    'params' => $params,
];
