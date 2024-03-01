<?php
defined('ROOT_PATH') or define('ROOT_PATH', dirname(dirname(__DIR__)));
defined('VENDOR_PATH') or define('VENDOR_PATH', ROOT_PATH . '/vendor');
defined('RUNTIME_PATH') or define('RUNTIME_PATH', ROOT_PATH . '/runtime');
defined('ETC_PATH') or define('ETC_PATH', ROOT_PATH . '/app/etc');

if (file_exists(ETC_PATH . '/env.php')) {
    defined('ENV_PATH') or define('ENV_PATH', ETC_PATH . '/env.php');
} else {
    defined('ENV_PATH') or define('ENV_PATH', ETC_PATH . '/env.test.php');
}

defined('COMMON_CONFIG_PATH') or define('COMMON_CONFIG_PATH', ETC_PATH . '/config/common.php');
defined('CLI_CONFIG_PATH') or define('CLI_CONFIG_PATH', ETC_PATH . '/config/cli.php');
defined('API_CONFIG_PATH') or define('API_CONFIG_PATH', ETC_PATH . '/config/api.php');

use yii\helpers\FileHelper;

$env = require(ENV_PATH);
defined('YII_DEBUG') or define('YII_DEBUG', $env['debug']);
defined('YII_ENV') or define('YII_ENV', $env['env']);

require VENDOR_PATH . '/autoload.php';
require VENDOR_PATH . '/yiisoft/yii2/Yii.php';

$rootAppDirectory = ROOT_PATH . '/app/code';
foreach (FileHelper::findDirectories($rootAppDirectory, ['recursive' => false]) as $directory) {
    Yii::setAlias('@' . basename($directory), $directory);
}
