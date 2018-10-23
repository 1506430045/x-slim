<?php
/**
 * Created by PhpStorm.
 * User: forward
 * Date: 2018/7/17
 * Time: 下午2:48
 */

namespace App\model\user;

use App\model\BaseModel;

class UserModel extends BaseModel
{
    const CHECK_SMS_CODE_SUCCESS = 1;   //验证码正确
    const CHECK_SMS_CODE_EXPIRED = 2;   //验证码超时失效
    const CHECK_SMS_CODE_FAILED = 3;    //验证码错误

    const SMS_CHECK_CODE_ERROR = 1026;          //验证码错误
    const SMS_CHECK_CODE_EXPIRE = 1027;         //验证码过期
    const SMS_CHECK_CODE_PHONE_REPEAT = 1028;   //验证码重复

    /**
     * 获取用户信息
     *
     * @param string $openId
     * @return array
     */
    public function getUserByOpenId(string $openId)
    {
        $userInfo = [];
        //sql
        return $userInfo;
    }
}