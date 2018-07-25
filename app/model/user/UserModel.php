<?php
/**
 * Created by PhpStorm.
 * User: forward
 * Date: 2018/7/17
 * Time: 下午2:48
 */

namespace App\model\user;


use App\model\asset\AssetModel;
use App\model\invite\InviteModel;
use App\model\task\TaskModel;
use App\model\user\InviteModel as UserInviteModel;
use App\model\BaseModel;
use App\model\PdoModel;
use Config\db\MysqlConfig;
use Util\AesUtil;
use Util\LoggerUtil;

class UserModel extends BaseModel
{
    const CHECK_SMS_CODE_SUCCESS = 1;   //验证码正确
    const CHECK_SMS_CODE_EXPIRED = 2;   //验证码超时失效
    const CHECK_SMS_CODE_FAILED = 3;    //验证码错误

    /**
     * 保存用户信息
     *
     * @param $rawData
     * @param $openId
     * @param string $inviteCode
     * @return int
     */
    public function addUser($rawData, $openId, $inviteCode = '')
    {
        $arr = json_decode($rawData, true);
        if (!is_array($arr) || empty($openId)) {
            return 0;
        }
        $inviter = $this->checkInviteCode($inviteCode);
        $data = [
            'openid' => $openId,
            'nickname' => $arr['nickName'],
            'gender' => $arr['gender'],
            'language' => $arr['language'],
            'city' => $arr['city'],
            'province' => $arr['province'],
            'country' => $arr['country'],
            'avatar_url' => $arr['avatarUrl'],
            'inviter' => $inviter,
            'phone' => '',
            'invite_code' => ''
        ];
        try {
            $userId = PdoModel::getInstance(MysqlConfig::$baseConfig)->table('candy_user')->insert($data);
            if ($userId > 0) {
                //保存用户信息到redis
                $data['id'] = $userId;
                (new redis\UserModel)->setOpenUserInfo($openId, $data);
            }
            if ($userId > 0 && $inviter) {
                //生成邀请任务工单
                (new InviteModel())->add($inviter, $userId);
            }
            if ($userId > 0) {
                //生成用户TB资产记录
                (new AssetModel())->createUserAsset($userId);
            }
            return $userId;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * 校验邀请码是否合法
     *
     * @param string $inviteCode
     * @return int
     */
    public function checkInviteCode($inviteCode = '')
    {
        if (empty($inviteCode)) {
            return 0;
        }
        $inviteUserId = UserInviteModel::decodeInviteCode($inviteCode);
        $checkCode = (new redis\UserModel)->getUserInviteCode($inviteUserId);
        return $inviteCode === $checkCode ? $inviteUserId : 0;
    }

    /**
     * 绑定手机号码
     *
     * @param $userId
     * @param $openId
     * @param $phone
     * @return int
     */
    public function bindPhone($userId, $openId, $phone)
    {
        $phoneEncrypt = AesUtil::encrypt($phone);
        $data = [
            'phone' => $phoneEncrypt
        ];
        try {
            //保存手机信息到Mysql
            $pdo = PdoModel::getInstance(MysqlConfig::$baseConfig)->table('candy_user');
            $rowCount = $pdo->where('id', '=', $userId)->where('phone', '=', '')->update($data);
            if ($rowCount > 0) {
                $userInfo = $this->getUserByOpenId($openId);
                //是否是被邀请 是的话需要给邀请人奖励
                if (!empty($userInfo['inviter'])) {
                    $description = sprintf("邀请%s", $userInfo['nickname']);
                    (new InviteModel())->inviteReward($userInfo['inviter'], $userId, $description);
                }
                //绑定手机需要给用户奖励
                (new TaskModel())->createBindPhoneTask($userId);
                //保存手机信息到redis
                $userInfo['phone'] = $phoneEncrypt;
                (new redis\UserModel)->setOpenUserInfo($openId, $userInfo);
            }
            return $rowCount;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * 绑定邀请码
     *
     * @param $userId
     * @param $openId
     * @param $inviteCode
     * @return int
     */
    public function bindInviteCode($userId, $openId, $inviteCode)
    {
        $data = [
            'invite_code' => $inviteCode
        ];
        try {
            //保存手机信息到Mysql
            $pdo = PdoModel::getInstance(MysqlConfig::$baseConfig)->table('candy_user');
            $rowCount = $pdo->where('id', '=', $userId)->where('invite_code', '=', '')->update($data);
            if ($rowCount > 0) {
                //保存邀请码信息到redis
                $userInfo = $this->getUserByOpenId($openId);
                $userInfo['invite_code'] = $inviteCode;
                $redisUserModel = new redis\UserModel;
                $redisUserModel->setOpenUserInfo($openId, $userInfo);
                $redisUserModel->setUserInviteCode($userId, $inviteCode);
            }
            return $rowCount;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * 获取用户信息
     *
     * @param string $openId
     * @return array
     */
    public function getUserByOpenId(string $openId)
    {
        if (empty($openId)) {
            return [];
        }
        $data = (new redis\UserModel)->getUserInfoByOpenId($openId);
        if (!empty($data)) {
            return $data;
        }
        $fields = ['id', 'openid', 'nickname', 'gender', 'language', 'city', 'province', 'country', 'avatar_url', 'inviter', 'phone', 'invite_code'];
        try {
            $data = PdoModel::getInstance(MysqlConfig::$baseConfig)->table('candy_user')->where('openid', '=', $openId)->getRow($fields);
            return $data;
        } catch (\Exception $e) {
            return [];
        }
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
        $ttl = 3600;    //todo
        return (new redis\UserModel)->setUserSmsCode($userId, $phone, $smsCode, $ttl);
    }

    /**
     * 获取短信验证码
     *
     * @param $userId
     * @param $phone
     * @return string
     */
    public function getUserSmsCode($userId, $phone)
    {
        return (new redis\UserModel)->getUserSmsCode($userId, $phone);
    }

    /**
     * 校验验证码
     *
     * @param $userId
     * @param $phone
     * @param string $verifyCode
     * @return int
     */
    public function checkUserSmsCode($userId, $phone, $verifyCode = '')
    {
        $checkCode = $this->getUserSmsCode($userId, $phone);
        if (empty($checkCode)) {    //已过期
            return self::CHECK_SMS_CODE_EXPIRED;
        }
        if ($verifyCode !== $checkCode) {   //验证码错误
            return self::CHECK_SMS_CODE_EXPIRED;
        }
        return self::CHECK_SMS_CODE_SUCCESS;   //验证码正确
    }

    /**
     * 批量获取用户信息
     *
     * @param array $userIds
     * @param array $fileds
     * @return array
     */
    public function getUsersByUserIds(array $userIds, array $fileds = [])
    {
        try {
            $list = PdoModel::getInstance(MysqlConfig::$baseConfig)->table('candy_user')->whereIn('id', $userIds)->getList($fileds);
            if (empty($list)) {
                return [];
            }
            return array_combine(array_column($list, 'id'), $list);
        } catch (\Exception $e) {
            return [];
        }
    }
}