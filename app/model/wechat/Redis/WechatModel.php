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
    const TOKEN_KEY = "token:%s";                     //token
    const OPEN_TOKEN = "open_id:%s:token";            //openId token

    /**
     * 设置3rdsession
     *
     * @param string $openId
     * @param string $token
     * @param $val
     * @param int $ttl
     * @return bool
     */
    public function set3rdSession(string $openId, string $token, $val, $ttl = 86400)
    {
        $redis = RedisModel::getInstance(RedisConfig::$baseConfig)->redis;
        $oldToken = $redis->get(sprintf(self::OPEN_TOKEN, $openId));
        $redis = $redis->multi();
        if (!empty($oldToken)) {   //需要从redis删除原token
            $redis->set(sprintf(self::TOKEN_KEY, $token), $val, $ttl);
            $redis->set(sprintf(self::OPEN_TOKEN, $openId), $token, $ttl);
            $redis->del(sprintf(self::TOKEN_KEY, $oldToken));
            $re = $redis->exec();
            return $re['0'];
        } else {
            $redis->set(sprintf(self::TOKEN_KEY, $token), $val, $ttl);
            $redis->set(sprintf(self::OPEN_TOKEN, $openId), $token, $ttl);
            $re = $redis->exec();
            return $re['0'];
        }
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