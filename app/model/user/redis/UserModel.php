<?php
/**
 * Created by PhpStorm.
 * User: forward
 * Date: 2018/7/19
 * Time: 下午8:21
 */

namespace App\model\user\redis;


use App\model\RedisModel;
use Config\db\RedisConfig;
use Util\LoggerUtil;

class UserModel
{
    const OPEN_USER_INFO_HASH = 'openid:user_info:hash';            //openid -> user_info hash
    const USER_INVITE_CODE_HASH = 'user_id:%s:invite_code:hash';    //用户邀请码
    const USER_PHONE_SMS_CODE = 'user:%s:phone:%s:sms_code';        //用户短信
    const USER_SIGN_IN_FLAG = 'user:%s:sign_in:flag';   //用户签到标记
    const USER_INVITEE_BITMAP = 'user:invitee:bitmap';  //所有被邀请的人

    /**
     * 保存用户邀请码到redis hash
     *
     * @param $userId
     * @param string $inviteCode
     * @return bool|int
     */
    public function setUserInviteCode($userId, $inviteCode = '')
    {
        if (empty($inviteCode)) {
            return false;
        }
        return RedisModel::getInstance(RedisConfig::$baseConfig)->redis->hSet(self::USER_INVITE_CODE_HASH, $userId, $inviteCode);
    }

    /**
     * 获取用户邀请码
     *
     * @param int $userId
     * @return string
     */
    public function getUserInviteCode(int $userId)
    {
        return RedisModel::getInstance(RedisConfig::$baseConfig)->redis->hGet(self::USER_INVITE_CODE_HASH, $userId);
    }

    /**
     * 保存用户基础信息到redis hash
     *
     * @param $openId
     * @param array $data
     * @return bool|int
     */
    public function setOpenUserInfo($openId, $data = [])
    {
        if (empty($openId) || empty($data)) {
            return false;
        }
        return RedisModel::getInstance(RedisConfig::$baseConfig)->redis->hSet(self::OPEN_USER_INFO_HASH, $openId, json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    /**
     * 获取用户基础信息
     *
     * @param string $openId
     * @return array
     */
    public function getUserInfoByOpenId(string $openId)
    {
        $json = RedisModel::getInstance(RedisConfig::$baseConfig)->redis->hGet(self::OPEN_USER_INFO_HASH, $openId);
        return json_decode($json, true) ?: [];
    }

    /**
     * 设置用户短信验证码
     *
     * @param $userId
     * @param $phone
     * @param $smsCode
     * @param int $ttl
     * @return int
     */
    public function setUserSmsCode($userId, $phone, $smsCode, $ttl = 300)
    {
        $key = sprintf(self::USER_PHONE_SMS_CODE, $userId, $phone);
        $redis = RedisModel::getInstance(RedisConfig::$baseConfig)->redis;
        $re1 = $redis->setnx($key, $smsCode);
        $re2 = false;
        if ($re1) {
            $re2 = $redis->expire($key, $ttl);
        }

        if ($re1 && !$re2) {
            LoggerUtil::getInstance()->alert(sprintf("设置用户短信验证码异常, params=%s, re1=%d, re2=%d"));
        }

        if (!$re1) {    //验证码在有效期内，不可重复设置
            return 2;
        }
        if ($re1 && $re2) { //验证码设置成功
            return 1;
        }
        return 0;   //设置失败或设置异常
    }

    /**
     * 获取用户短信验证码
     *
     * @param $userId
     * @param $phone
     * @return string
     */
    public function getUserSmsCode($userId, $phone)
    {
        $key = sprintf(self::USER_PHONE_SMS_CODE, $userId, $phone);
        $code = RedisModel::getInstance(RedisConfig::$baseConfig)->redis->get($key);
        return $code ?: '';
    }

    /**
     * 设置用户当日签到flag
     *
     * @param int $userId
     * @return int
     */
    public function setUserSignFlag(int $userId)
    {
        $timeEnd = strtotime(date('Y-m-d 23:59:59'));   //当日23:59:59
        $key = sprintf(self::USER_SIGN_IN_FLAG, $userId);
        $redis = RedisModel::getInstance(RedisConfig::$baseConfig)->redis;
        $re1 = $redis->setnx($key, date('Y-m-d H:i:s'));
        $re2 = false;
        if ($re1) { //签到成功
            $re2 = $redis->expireAt($key, $timeEnd);
        }
        if ($re1 && !$re2) {
            LoggerUtil::getInstance()->alert(sprintf("签到数据异常, params=%s, re1=%d, re2=%d"));
        }

        if (!$re1) {        //当日已签到，不可重复签到
            return 2;
        }
        if ($re1 && $re2) { //签到成功
            return 1;
        }
        return 0;   //设置失败或设置异常
    }

    /**
     * 是否已该被邀请者生成邀请任务
     *
     * @param int $invitee 被邀请者
     * @return int
     */
    public function getInvitee(int $invitee)
    {
        return RedisModel::getInstance(RedisConfig::$baseConfig)->redis->getBit(self::USER_INVITEE_BITMAP, $invitee);
    }

    /**
     * 记录该被邀请者已生成邀请任务
     *
     * @param int $invitee
     * @return int
     */
    public function setInviteeToMap(int $invitee)
    {
        return RedisModel::getInstance(RedisConfig::$baseConfig)->redis->setBit(self::USER_INVITEE_BITMAP, $invitee, 1);
    }
}