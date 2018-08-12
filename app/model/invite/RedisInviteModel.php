<?php
/**
 * 邀请
 *
 * User: forward
 * Date: 2018/8/12
 * Time: 下午5:17
 */

namespace App\model\invite;


use App\model\RedisModel;
use Config\db\RedisConfig;

class RedisInviteModel
{
    const INVITE_COUNTER = 'invite:counter';    //zset  score邀请人数 value邀请人

    /**
     * 增加邀请人数
     *
     * @param int $inviter
     * @return float
     */
    public static function incrInviteNumber(int $inviter = 0)
    {
        if (empty($inviter)) {
            return 0.0;
        }
        return RedisModel::getInstance(RedisConfig::$baseConfig)->redis->zIncrBy(self::INVITE_COUNTER, 1, $inviter);
    }

    /**
     * 邀请人数排行榜
     *
     * @return array
     */
    public static function getInviteRankList()
    {
        return RedisModel::getInstance(RedisConfig::$baseConfig)->redis->zRevRange(self::INVITE_COUNTER, 0, 99, true);
    }
}