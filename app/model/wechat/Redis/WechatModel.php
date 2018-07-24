<?php
/**
 * Created by PhpStorm.
 * User: forward
 * Date: 2018/7/17
 * Time: 下午8:53
 */

namespace App\model\wechat\Redis;


use App\model\RedisModel;
use Config\db\RedisConfig;

class WechatModel
{
    const TOKEN_KEY = "token:%s";

    /**
     * 设置3rdsession
     *
     * @param $token
     * @param $val
     * @param int $ttl
     * @return bool
     */
    public function set3rdSession($token, $val, $ttl = 3600)
    {
        $key = sprintf(self::TOKEN_KEY, $token);
        return RedisModel::getInstance(RedisConfig::$baseConfig)->redis->set($key, $val, $ttl);
    }

    /**
     * 3rdsession是否存在
     *
     * @param $token
     * @return bool
     */
    public function exists3rdSession($token)
    {
        $key = sprintf(self::TOKEN_KEY, $token);
        return RedisModel::getInstance(RedisConfig::$baseConfig)->redis->exists($key);
    }

    /**
     * 获取3rdsession
     *
     * @param $token
     * @return bool|string
     */
    public function get3rdSession($token)
    {
        $key = sprintf(self::TOKEN_KEY, $token);
        return RedisModel::getInstance(RedisConfig::$baseConfig)->redis->get($key);
    }
}