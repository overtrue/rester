<?php

return array(

    /**
     * 是否开启debug模式
     */
    'debug' => true,

    /**
     * 返回值类型
     *
     * <pre>
     * - 'auto':
     *     自动判断，默认根据header('Accept') 的值自动判断，
     *     或者在请求中传递__format=xml来读取。
     *
     * - 'json':
     *     返回json格式，如果存在callback则自动生成 jsonp 格式
     *
     * - 'xml':
     *     生成xml格式
     *
     * - 'disabled'：
     *     禁用返回类型，由开发者自定义
     * </pre>
     */
    'response_format' => 'auto',
);