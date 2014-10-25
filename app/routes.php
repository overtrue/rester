<?php

//更多使用方法请阅读文档:
//http://docs.slimframework.com/#Routing-Overview

$app->get('/', 'HomeController:index');

$app->group('/api', function() use ($app){
    //user
    $app->get('/users',              'UserController:index');
    $app->get('/users/:user_id',          'UserController:show');
    $app->get('/users/:user_id/articles', 'ArticleController:index');

    //article
    $app->get('/articles',                                  'ArticleController:index');
    $app->get('/articles/:article_id',                      'ArticleController:show');
    $app->get('/articles/:article_id/chapters',             'ChapterController:index');
    $app->get('/articles/:article_id/chapters/:chapter_id', 'ChapterController:show');

    //chapter
    $app->get('/chapters',              'ChapterController:index');
    $app->get('/chapters/:chapter_id',  'ChapterController:show');
});