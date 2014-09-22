<?php

use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

define('APP_START', microtime(true));

require __DIR__ . '/helpers.php';
$loader = require __DIR__ . '/../vendor/autoload.php';

define('ROOT_PATH', __DIR__ . '/../');
define('APP_PATH', __DIR__ . '/../app');

// detect application environment.
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

// Set the cache manager instance used by connections... (optional)
//$capsule->setCacheManager(...);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

require APP_PATH . '/routes.php';

