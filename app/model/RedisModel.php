<?php
/**
 * Created by PhpStorm.
 * User: forward
 * Date: 2018/7/16
 * Time: 上午11:42
 */

namespace App\model;


class RedisModel
{
    private static $_instances = [];
    public $redis;

    /**
     * 构造方法
     *
     * RedisModel constructor.
     * @param array $config
     */
    private function __construct(array $config)
    {
        try {
            $this->redis = new \Redis();
            $this->redis->connect($config['host'], $config['port'], $config['timeout']);
            $this->redis->auth($config['auth']);
        } catch (\Exception $e) {
            //Log
        }
    }

    /**
     * 获取实例
     *
     * @param array $config
     * @return RedisModel
     */
    public static function getInstance(array $config)
    {
        ksort($config);
        $key = md5(serialize($config));
        if (isset(self::$_instances[$key]) && self::$_instances[$key] instanceof self) {
            return self::$_instances[$key];
        }
        self::$_instances[$key] = new self($config);
        return self::$_instances[$key];
    }

    /**
     * 禁止克隆
     */
    public function __clone()
    {
        trigger_error('redis instance can not clone', E_USER_ERROR);
    }
}