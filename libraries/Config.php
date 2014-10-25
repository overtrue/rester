<?php

namespace Rester;

use ArrayAccess;

/**
 * Config.php
 *
 * @author Carlos <anzhengchao@gmail.com>
 * @date   [2014-09-17 15:55]
 */

/**
 *  配置文件服务
 *  根据不同的运行环境确定不同的配置项
 */
class Config implements ArrayAccess
{

    /**
     * 配置文件路径
     *
     * @var string
     */
    protected $path;

    /**
     * 运行环境名称
     *
     * @var array
     */
    protected $env = '';

    /**
     * 临时设置
     *
     * @var array
     */
    protected $tempConfig = [];

    /**
     * 初始化配置
     *
     * @param string $path 配置文件路径
     * @param string $env  运行环境名称
     *
     */
    public function __construct($path, $env)
    {
        $this->path = $path;
        if (is_dir($path . '/' . $env)) {
            $this->env = $env;
        }
    }

    /**
     * 创建配置实例
     *
     * @param string $path 配置文件路径
     * @param string $env  运行环境名称
     *
     * @return Config
     */
    public static function make($path, $env)
    {
        return new static($path, $env);
    }

    /**
     * 读取配置
     *
     * @return array 配置文件信息
     */
    public function get($key, $default = null)
    {
        if (isset($this->tempConfig[$key])) {
            return $this->tempConfig[$key];
        }

        list($namespace, $group, $item) = $this->_parseKey($key);

        $baseConfigFile = $this->path . '/' . $namespace . '.php';
        $envConfigFile  = $this->path . '/' . $this->env. '/' . $namespace . '.php';

        if (!file_exists($baseConfigFile)) {
            return $default;
        }

        $config = $this->load($baseConfigFile);

        if (empty($config)) {
            return $default;
        }

        $envConfig = $this->load($envConfigFile);

        if ($envConfig) {
            $config = array_merge($config, $envConfig);
        }

        $configArray = [$namespace => $config];

        $res = array_get($configArray, $key);

        return is_null($res) ? $default : $res;
    }

    /**
     * 检测是否有配置
     *
     * @param string $key 配置名
     *
     * @return boolean
     */
    public function has($key)
    {
        return null !== $this->get($key);
    }

    /**
     * 修改配置
     *
     * @param string $key   名称
     * @param mixed  $value 值
     */
    public function set($key, $value)
    {
        $this->tempConfig[$key] = $value;
    }

    /**
     * 读取配置文件
     *
     * @param string $path 文件路径
     *
     * @return array
     */
    protected function load($path)
    {
        if (!stream_resolve_include_path($path)) {
            return false;
        }

        $config = include $path;

        if (!is_array($config)) {
            return false;
        }

        return $config;
    }

    /**
     * 解析配置key
     *
     * @param string $key
     *
     * @return array
     */
    protected function _parseKey($key)
    {
        $arr = explode('.', $key);

        return [
            $arr[0],
            !isset($arr[0]) ? $arr[1] : '',
            !isset($arr[0]) ? $arr[2] : '',
        ];
    }

    public function offsetGet($key)
    {
        return $this->get($key);
    }

    public function offsetSet($key, $value)
    {
        return $this->set($key, $value);
    }

    public function offsetExists($key)
    {
        return $this->has($key);
    }

    public function offsetUnset($key)
    {
        return $this->set($key, null);
    }
}