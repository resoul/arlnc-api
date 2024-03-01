<?php
/**
 * @var $env array
 */
use yii\console\controllers\MigrateController;
use yii\log\Dispatcher;
use yii\log\FileTarget;

$env = require(ENV_PATH);
$config = require(COMMON_CONFIG_PATH);

$config['controllerMap']['migrate'] = [
    'class' => MigrateController::class,
    'migrationNamespaces' => [
        'Middleware\Framework\Queue\Migration'
    ]
];
$config['components']['log'] = [
    'class' => Dispatcher::class,
    'targets' => [
        [
            'class' => FileTarget::class,
            'levels' => ['error', 'warning'],
            'logFile' => RUNTIME_PATH . '/logs/cli.log'
        ]
    ],
    'traceLevel' => YII_DEBUG ? 3 : 0
];

return $config;
