<?php
use yii\web\Application;
use yii\base\InvalidConfigException;

require __DIR__ . '/../app/etc/autoload.php';
$config = require_once(API_CONFIG_PATH);

try {
    (new Application($config))->run();
} catch (InvalidConfigException $e) {
    echo "<pre>{$e->getMessage()}</pre>";
}
