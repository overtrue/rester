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
     * @param arrat $data 输出数据
     *
     * @return \Slim\Http\Response
     */
    protected function json($data)
    {
        $this->response->headers->set('content-type', 'application/json');

        if ($this->config->get('app.debug')) {
            $this->response->headers->set('X-Time-Usage',round(microtime(true) - APP_START, 6));
        }
        
        return $this->response->setBody(json_encode($data, JSON_UNESCAPED_UNICODE));
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
    public function error($message, $code, $errors = [])
    {

        if (!isset($code, $this->errorCodes)) {
            throw new Exception("The error code '{$code}' not a valid error code.");
        }

        if ($this->config->get('app.debug')) {
            $this->response->headers->set('X-Time-Usage',round(microtime(true) - APP_START, 6));
        }

        $this->response->setStatus($code);

        $this->response->headers->set('content-type', 'application/json');

        $data = [
            'message' => $message,
        ];

        empty($errors) || $data['errors'] = $errors;

        $this->response->setBody(json_encode($data, JSON_UNESCAPED_UNICODE));

        self::$app->stop();
    }

    /**
     * 验证输入
     *
     * @param array $input 
     * @param arrat $rules 
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
}