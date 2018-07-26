<?php
/**
 * 缓存
 *
 * User: forward
 * Date: 2018/6/28
 * Time: 上午11:29
 */

namespace Util;


use Config\db\RedisConfig;
use App\model\RedisModel;

class CacheUtil
{
    /**
     * 设置redis缓存
     *
     * @param string $key
     * @param array $data
     * @param int $timeout
     * @return bool
     */
    public static function setCache(string $key, array $data, $timeout = 0)
    {
        if (empty($key)) {
            return false;
        }
        return RedisModel::getInstance(RedisConfig::$baseConfig)->redis->set($key, json_encode($data, JSON_UNESCAPED_UNICODE), $timeout);
    }

    /**
     * 获取缓存
     *
     * @param string $key
     * @return array
     */
    public static function getCache(string $key)
    {
        if (empty($key)) {
            return [];
        }
        $json = RedisModel::getInstance(RedisConfig::$baseConfig)->redis->get($key);
        return json_decode($json, true) ?: [];
    }

    /**
     * 删除缓存
     *
     * @param string $key
     * @return int
     */
    public static function delCache(string $key)
    {
        if (empty($key)) {
            return 0;
        }
        return RedisModel::getInstance(RedisConfig::$baseConfig)->redis->delete($key);
    }
}