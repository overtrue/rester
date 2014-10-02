<?php

//更多使用方法请阅读文档:
//http://docs.slimframework.com/#Routing-Overview

$app->get('/', 'HomeController:index');

$app->group('/api', function() use ($app){
    $app->get('/articles', 'ArticleController:index');
    $app->get('/articles/:id', 'ArticleController:show');
});