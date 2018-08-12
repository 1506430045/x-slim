<?php
/**
 * 奖励相关
 *
 * User: forward
 * Date: 2018/8/12
 * Time: 下午3:21
 */

namespace App\model\reward;


use App\model\RedisModel;
use Config\db\RedisConfig;

class RedisRewardModel
{
    const CURRENCY_ID = 1;                          //星钻货币ID
    const RANK_NUMBER = 100;                        //排名数量
    const RANK_LAST_NUMBER = 'rank:%d:last:number'; //上次排名最后一名的星钻数量

    /**
     * 设置上次排名最后一名星钻数量
     *
     * @param float $number
     * @return bool
     */
    public static function setRankLastNumber(float $number = 0.0)
    {
        $key = sprintf(self::RANK_LAST_NUMBER, self::RANK_NUMBER);
        return RedisModel::getInstance(RedisConfig::$baseConfig)->redis->set($key, $number);
    }

    /**
     * 获取上次排名最后一名星钻数量
     *
     * @param string $key
     * @return float
     */
    public static function getRankLastNumber()
    {
        $key = sprintf(self::RANK_LAST_NUMBER, self::RANK_NUMBER);
        $number = RedisModel::getInstance(RedisConfig::$baseConfig)->redis->get($key);
        return empty($number) ? 0.0 : floatval($number);
    }
}