<?php
/**
 * helpers.php
 *
 * @author Carlos <anzhengchao@gmail.com>
 * @date   [2014-07-17 15:51]
 */

/**
 * 生成url
 *
 * @param string $target
 *
 * @return string
 */
function urlto($target)
{
    return cfg('app.base_url', '/') . ltrim($target, '/');
}

/**
 * 静态资源
 *
 * @param string $target
 *
 * @return string
 */
function assets($target)
{
    return urlto('/assets/') . ltrim($target, '/');
}

/**
 * 获取应用运行环境
 *
 * @return string
 */
function env($test = null)
{
    $envs = include __DIR__ . '/env.php';
    foreach ($envs as $env => $hostnames) {
        if (in_array(gethostname(), $hostnames) || in_array('*', $hostnames)) {
            return $test ? $env == $test : $env;
        }
    }

    return $test ? 'production' === $test : 'production';
}

/**
 * 获取App实例
 *
 * @return \Slim\Slim
 */
function app()
{
    return \Slim\Slim::getInstance();
}


/**
 * 读取配置文件
 *
 * @param string $key
 * @param string $default
 *
 * @return mixed
 */
function cfg($key, $default = null)
{
    return app()->config->get($key, $default);
}


/**
 * 获取当前输入
 *
 * @param string $key
 * @param mixed $default
 *
 * @return mixed
 */
function input($key, $default = null)
{
    return array_get(app()->request()->params(), $key, $default);
}

/**
 * 获取上一次输入
 *
 * @param string $key
 * @param mixed $default
 *
 * @return mixed
 */
function old($key, $default = null)
{
    if (empty($_SESSION['slim.flash'])) {
        return $default;
    }

    return array_get($_SESSION['slim.flash'], "__old_input.$key", $default);
}

/**
 * 获取来源页链接
 *
 * @param string $default
 *
 * @return string
 */
function back($default = null)
{
    if (!empty($_SERVER['HTTP_REFERER'])) {
        return $_SERVER['HTTP_REFERER'];
    }

    if (empty($_SESSION['slim.flash'])) {
        return $default;
    }

    return array_get($_SESSION['slim.flash'], "last_url", $default);
}

/**
 * 包含模板
 *
 * @param string $alias
 *
 * @return string
 */
function partial($alias, $data = array())
{
    $template = str_finish(app()->config('templates.path'), '/') . view_file($alias);

    extract(array_merge(app()->view->getData(), $data));

    return include $template;
}

/**
 * 生成视图
 *
 * @param string $alias
 *
 * @return string
 */
function view_file($alias)
{
    if (stripos($alias, '.php') > 0) {
        $alias = substr($alias, 0, -4);
    }

    return str_replace('.', DIRECTORY_SEPARATOR, trim($alias, DIRECTORY_SEPARATOR)) . '.php';
}

/**
 * 加密字符串
 *
 * @param string $input
 *
 * @return string
 */
function encrypt($input) {
    $securekey = getSecurekey();
    return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $securekey, $input, MCRYPT_MODE_ECB);
}

/**
 * 解密字符串
 *
 * @param string $input
 *
 * @return string
 */
function decrypt($input) {
    $securekey = getSecurekey();
    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $securekey, $input, MCRYPT_MODE_ECB));
}

/**
 * 获取配置文件路径
 *
 * @return string
 */
function config_path()
{
    return path_join(APP_PATH, 'config');
}

/**
 * 生成json输出
 *
 * @param arrat $data 输出数据 *
 *
 * @return \Slim\Http\Response
 */
function json($data, $status = 200)
{
    return setJsonResponse($data, $status);
}

/**
 * jsonp格式输出
 *
 * @param array  $data     输出数据 *
 * @param string $callback 回调函数 (optional)
 *
 * @return \Slim\Http\Response
 */
function jsonp($data, $callback = '')
{
    $app = app();

    $json = json_encode($data, JSON_UNESCAPED_UNICODE);

    $callback = $callback ?: $app->request->get('callback');

    $body = $callback ? "{$callback}($json)" : $json;

    return setJsonResponse($body, 200);
}

/**
 * 错误输出
 *
 * @param string $message 错误消息 *
 * @param int    $code    错误码 *
 * @param array  $errors  错误明细 (optional)
 *
 * @return \Slim\Http\Response
 */
function error($message, $status, $errors = [])
{
    $app = app();

    /**
     * 允许的错误码
     *
     * @var array
     */
    $errorCodes = [
        400 => 'Bad Request',
        422 => 'Unprocessable Entity',
    ];

    if (!isset($status, $errorCodes)) {
        throw new Exception("The error code '{$status}' not a valid error code.");
    }

    $data = [
        'message' => $message,
    ];

    empty($errors) || $data['errors'] = $errors;

    setJsonResponse($data, $status);

    self::$app->stop();
}

/**
 * 验证输入
 *
 * @param array   $input  需要验证的数据 *
 * @param arrat   $rules  验证规则 *
 * @param boolean $return 是否返回验证结果 (optional, 默认: false)
 *
 * @return array If $return is true and validation failed.
 */
function validate($input, $rules, $return = false)
{
    $app = app();

    $validator = $app->validator->make($input, $rules);

    if ($validator->fails()) {
        if ($return) {
            return $validator->messages->all();
        }

        $app->error('Validation Failed.', 422, $validator->messages());
    }

    return true;
}

/**
 * 输出json
 *
 * @param mixed   $body   内容
 * @param integer $status 状态码 (optional, 默认:200)
 *
 * @return \Slim\Http\Response
 */
function setJsonResponse($body, $status = 200)
{
    $app = app();
    if ($app->config->get('app.debug')) {
        $app->response->headers->set('X-Time-Usage',round(microtime(true) - APP_START, 6));
    }

    $app->response->setStatus($status);

    $app->response->headers->set('content-type', 'application/json');

    is_string($body) || $body = json_encode($body, JSON_UNESCAPED_UNICODE);

    return $app->response->setBody($body);
}

/**
 * 获取客户端IP
 */
if(!function_exists('get_client_ip')){

    function get_client_ip()
    {
        $unknown = 'unknown';

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])

            && $_SERVER['HTTP_X_FORWARDED_FOR']

            && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {

                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

        } elseif(isset($_SERVER['REMOTE_ADDR'])

                && $_SERVER['REMOTE_ADDR']

                && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {

                $ip = $_SERVER['REMOTE_ADDR'];
            }

        if (false !== strpos($ip, ','))
        {
            $ip = reset(explode(',', $ip));
        }
        return ip2long($ip);
    }
}
