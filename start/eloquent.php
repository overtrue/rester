<?php

use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection($config->get('database.mysql'));

$capsule->setEventDispatcher(new Dispatcher(new Container));


// 模型缓存
//$capsule->setCacheManager(...);

// 注册全局静态类
$capsule->setAsGlobal();

// 注册分页类
Capsule::setPaginator(function() use ($app, $config) {
    return new Paginator($app->request, $config->get('pager', 'page'));
});

// 启动 Eloquent ORM...
$capsule->bootEloquent();