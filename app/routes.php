<?php

//更多使用方法请阅读文档:
//http://docs.slimframework.com/#Routing-Overview

$app->get('/', 'HomeController:index');

//http://yousite/hello/overtrue
$app->get('/hello/:username', function($username){
    echo "Hello $username";
});
