<?php

use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

define('APP_START', microtime(true));

require __DIR__ . '/helpers.php';
define('ROOT_PATH', __DIR__ . '/../');
define('APP_PATH', ROOT_PATH . '/app');

$loader = require __DIR__ . '/../vendor/autoload.php';

// 获取应用环境名称
define('_ENV', env());

$config = Config::make(ROOT_PATH . '/config', _ENV);
 
$app = new \Slim\Slim([
    'debug' => $config->get('app.debug'),
]);

$app->config = $config;

Controller::$app = $app;

$capsule = new Capsule;
$capsule->addConnection($config->get('database.mysql'));

$capsule->setEventDispatcher(new Dispatcher(new Container));

// 模型缓存
//$capsule->setCacheManager(...);

// 注册全局静态类
$capsule->setAsGlobal();

// 启动 Eloquent ORM...
$capsule->bootEloquent();

require APP_PATH . '/error.php';
require APP_PATH . '/routes.php';

return $app;

