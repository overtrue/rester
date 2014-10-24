<?php

$loader = require __DIR__ . '/../vendor/autoload.php';

use Overtrue\Validation\Translator;
use Overtrue\Validation\Factory as ValidatorFactory;

define('APP_START', microtime(true));

// 助手函数
require __DIR__ . '/helpers.php';
define('ROOT_PATH', __DIR__ . '/../');
define('APP_PATH', ROOT_PATH . '/app');


// 获取应用环境名称
define('_ENV', env());

$config = Config::make(ROOT_PATH . '/config', _ENV);

//初始化验证类工厂对象
$validator = new ValidatorFactory(new Translator);

$app = new \Slim\Slim([
    'debug' => $config->get('app.debug'),
]);

$app->config    = $config;
$app->validator = $validator;

// 初始化Eloquent
require __DIR__ . '/eloquent.php';

// 错误处理
require APP_PATH . '/error.php';

// 中间件
require APP_PATH . '/middlewares.php';

// 包含用户路由
require APP_PATH . '/routes.php';



return $app;

