<?php
/**
 * @var $env array
 */

use yii\queue\db\Queue;
use yii\mutex\MysqlMutex;
use yii\db\Connection;
use yii\symfonymailer\Mailer;
use yii\caching\FileCache;

$env = require(ENV_PATH);

$config = [
    'id' => 'http-rest-api-service',
    'name' => 'Http Rest API Service',
    'basePath' => ROOT_PATH,
    'runtimePath' => RUNTIME_PATH,
    'vendorPath' => VENDOR_PATH,
    'bootstrap' => ['log', 'queue'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'timezone' => 'Europe/London',
    'components' => [
        'queue' => [
            'class' => Queue::class,
            'db' => 'db',
            'tableName' => '{{%queue}}',
            'channel' => 'default',
            'mutex' => MysqlMutex::class
        ],
        'cache' => [
            'class' => FileCache::class
        ],
        'mailer' => [
            'class' => Mailer::class,
            'viewPath' => '@Middleware/Framework/Mail',
            'useFileTransport' => $env['mailer.file.transport'],
            'transport' => [
                'scheme' => 'smtps',
                'host' => $env['mailer.host'],
                'username' => $env['mailer.username'],
                'password' => $env['mailer.password'],
                'port' => 465,
                'dsn' => 'native://default'
            ]
        ],
        'db' => [
            'class' => Connection::class,
            'charset' => 'utf8mb4',
            'dsn' => $env['db.dsn'],
            'username' => $env['db.username'],
            'password' => $env['db.password']
        ]
    ],
    'params' => $env['params'],
];
// https://github.com/yiisoft/yii2-symfonymailer
return $config;
