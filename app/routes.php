<?php

//更多使用方法请阅读文档:
//http://docs.slimframework.com/#Routing-Overview

$app->get('/', 'HomeController:index');

$app->group('/users', function() {
    return array(
            array(
                'username' => 'Carlos',
                'nickname' => 'overtrue',
                'email'    => 'anzhengchao@gmail.com',
            ),

            array(
                'username' => 'SomeBody',
                'nickname' => 'sbdy',
                'email'    => 'sbdy@sb.com',
            ),

           );
});