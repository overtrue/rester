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
        self::$app || self::$app = \Slim\Slim::getInstance();

        $this->request   = self::$app->request();
        $this->response  = self::$app->response();
        $this->config    = self::$app->config;
        $this->validator = self::$app->validator;
        $this->init();
    }

    /**
     * 初始调用方法
     *
     * @return void
     */
    public function init(){}
}