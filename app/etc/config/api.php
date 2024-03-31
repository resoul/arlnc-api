<?php
/**
 * @var $env array
 */

use yii\log\FileTarget;
use yii\web\JsonParser;
use yii\web\Response;
use yii\web\UrlManager;
use Airlance\Account\Account;
use Airlance\Media\Media;
use Airlance\Theme\Theme;

$env = require(ENV_PATH);
$config = require(COMMON_CONFIG_PATH);

$config['modules'] = [
    'account' => [
        'class' => Account::class,
        'controllerNamespace' => 'Airlance\Account\Controller\Rest'
    ],
    'theme' => [
        'class' => Theme::class,
        'controllerNamespace' => 'Airlance\Theme\Controller\Rest'
    ],
    'media' => [
        'class' => Media::class,
        'controllerNamespace' => 'Airlance\Media\Controller\Rest'
    ]
];
$config['components']['request'] = [
    'baseUrl' => '',
    'cookieValidationKey' => $env['cookie.validation.key'],
    'parsers' => [
        'application/json' => JsonParser::class,
    ]
];
$config['components']['response'] = [
    'class' => Response::class,
    'format' => Response::FORMAT_JSON,
    'on beforeSend' => function ($event) {
        /**@var $response Response */
        $response = $event->sender;
        if ($response->data !== null && Yii::$app->request->get('suppress_response_code')) {
            $response->data = [
                'success' => $response->isSuccessful,
                'data' => $response->data
            ];
            $response->statusCode = 200;
        }
    }
];
$config['components']['urlManager'] = [
    'class' => UrlManager::class,
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        '' => 'theme/updates/get-version',
        'media/image/<user_id>/<folder_id>/<file>' => 'media/image/view',
        'media/movie/<slug>' => 'media/movie/movie',
        'media/movie/<slug>/<slug2>/<slug3>' => 'media/movie/series',
        '<_m:[\w\-]+>/<_c:[\w\-]+>' => '<_m>/<_c>/index',
        '<_m:[\w\-]+>/<_c:[\w\-]+>/<id:\d+>' => '<_m>/<_c>/view',
        '<_m:[\w\-]+>/<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_m>/<_c>/<_a>',
        '<_m:[\w\-]+>/<_c:[\w\-]+>/<_a:[\w-]+>' => '<_m>/<_c>/<_a>',
    ]
];
$config['components']['log'] = [
    'traceLevel' => YII_DEBUG ? 3 : 0,
    'targets' => [
        [
            'class' => FileTarget::class,
            'levels' => ['error', 'warning'],
            'logFile' => RUNTIME_PATH . '/logs/api.log'
        ],
    ]
];

return $config;
