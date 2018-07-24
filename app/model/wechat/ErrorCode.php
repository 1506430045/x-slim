<?php
/**
 * 微信小程序错误码
 *
 * User: forward
 * Date: 2018/7/17
 * Time: 下午6:21
 */

namespace App\model\wechat;


class ErrorCode
{
    public static $OK = 0;
    public static $IllegalAesKey = -41001;
    public static $IllegalIv = -41002;
    public static $IllegalBuffer = -41003;
    public static $DecodeBase64Error = -41004;
}