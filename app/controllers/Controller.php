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
    protected $request;
    protected $reponse;
    public static $app;

    final function __construct()
    {
        if (is_null(self::$app)) {
            throw new Exception("Error Processing Request", 1);
        }

        $this->request  = self::$app->request();
        $this->response = self::$app->response();
        $this->config   = self::$app->config;
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
     * @return \Phalcon\Http\Response
     */
    public function json($data)
    {
        $this->response->headers->set('content-type', 'application/json');

        $data = [
            'status' => 1,
            'data'   => $data,
        ];

        if ($this->config->get('app.debug')) {
            $data['timeusage'] = round(microtime(true) - APP_START, 6);
        }
        
        return $this->response->setBody(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
}