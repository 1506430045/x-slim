<?php
/**
 * Redis配置
 *
 * User: forward
 * Date: 2018/6/25
 * Time: 下午6:41
 */

namespace Config\db;


class RedisConfig
{
    public static $baseConfig = [
        'host' => '127.0.0.1',
        'port' => '6379',
        'timeout' => 1,
        'auth' => ''
    ];
}