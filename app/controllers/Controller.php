<?php
/**
 * Controller.php
 * 
 * @author Carlos <anzhengchao@gmail.com>
 * @date   2014-08-13T12:11:19
 */

/**
 * 基础控制器
 */
abstract class Controller 
{
    /**
     * 输出对象
     *
     * @var \Slim\Http\Request
     */
    protected $request;

    /**
     * 输出对象
     *
     * @var \Slim\Http\Response
     */
    protected $reponse;

    /**
     * 允许的错误码
     *
     * @var array
     */
    protected $errorCodes = [
        400 => 'Bad Request',
        422 => 'Unprocessable Entity',
    ];

    /**
     * Slim容器对象
     *
     * @var \Slim\Slim
     */
    public static $app;


    /**
     * constructor
     */
    final function __construct()
    {
        if (is_null(self::$app)) {
            throw new Exception("Error Processing Request", 1);
        }

        $this->request   = self::$app->request();
        $this->response  = self::$app->response();
        $this->config    = self::$app->config;
        $this->validator = self::$app->validator;
        $this->init();
    }

    /**
     * 初始调用方法
     *
     * @return 
     */
    public function init(){}

    /**
     * 生成json输出
     *
     * @param arrat $data 输出数据 *
     *
     * @return \Slim\Http\Response
     */
    protected function json($data, $status = 200)
    {
        return $this->setJsonResponse($data, $status);
    }

    /**
     * jsonp格式输出
     *
     * @param array  $data     输出数据 *
     * @param string $callback 回调函数 (optional)
     *
     * @return \Slim\Http\Response
     */
    protected function jsonp($data, $callback = '')
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        $callback = $callback ?: $this->request->get('callback');

        $body = $callback ? "{$callback}($json)" : $json;

        return $this->setJsonResponse($body, 200);
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
    protected function error($message, $status, $errors = [])
    {

        if (!isset($code, $this->errorCodes)) {
            throw new Exception("The error code '{$status}' not a valid error code.");
        }

        $data = [
            'message' => $message,
        ];

        empty($errors) || $data['errors'] = $errors;

        $this->setJsonResponse($data, $status);

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
    protected function validate($input, $rules, $return = false)
    {
        $validator = $this->validator->make($input, $rules);

        if ($validator->fails()) {
            if ($return) {
                return $validator->messages->all();
            }

            $this->error('Validation Failed.', 422, $validator->messages());
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
    protected function setJsonResponse($body, $status = 200)
    {
        if ($this->config->get('app.debug')) {
            $this->response->headers->set('X-Time-Usage',round(microtime(true) - APP_START, 6));
        }

        $this->response->setStatus($status);

        $this->response->headers->set('content-type', 'application/json');

        is_string($body) || $data = json_encode($data, JSON_UNESCAPED_UNICODE);

        return $this->response->setBody($body);
    }
}