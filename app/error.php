<?php

/**
 * 错误处理
 */

$app->error(function (\Exception $e) use ($app) {
    $debug = $app->config->get('app.debug');

    $data = [
        'status' => 0,
        'error'  => $debug ? $e->getMessage() : 'Error Processing Request',
    ];

    if ($app->config->get('app.debug')) {
        $app->response->headers->set('X-Time-Usage',round(microtime(true) - APP_START, 6));
    }
    
    $app->response->headers->set('content-type', 'application/json');

    return $app->response->setBody(json_encode($data, JSON_UNESCAPED_UNICODE));
});

$app->notFound(function () use ($app) {
    throw new Exception("页面未找到", 1);
});