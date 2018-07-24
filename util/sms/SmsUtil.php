<?php
/**
 * 短信
 *
 * User: forward
 * Date: 2018/7/18
 * Time: 下午6:47
 */

namespace Util;


use Util\sms\AmazonSMS;
use Util\sms\Twilio;
use Util\sms\Yunpian;

class SmsUtil
{
    const CARRIER_AWS = 1;
    const CARRIER_TWILIO = 2;
    const CARRIER_YUNPIAN = 3;

    const SMS_CODE_LEN = 6;

    private static function send($operator, $regionId, $phone, $content)
    {
        $mobile = $regionId + $phone;
        switch ($operator) {
            case self::CARRIER_YUNPIAN:
                Yunpian::send($regionId, $phone, $content);
                break;
            case self::CARRIER_AWS:
                AmazonSMS::send($mobile, $content);
                break;
            case self::CARRIER_TWILIO:
                $res = Twilio::send($mobile, $content);
                break;
            default:
                break;
        }
    }

    /**
     * 智能匹配模板，举例：【THINKBit】您的验证码是920201
     *
     * @param $operator
     * @param $regionId
     * @param $mobile
     * @param $code
     */
    public static function sendCode($operator, $regionId, $mobile, $code)
    {
        $content = sprintf("【ThinkBit】您的验证码是%s", $code);
        self::send($operator, $regionId, $mobile, $content);
    }

    /**
     * 生成短信验证码
     *
     * @return string
     */
    public static function generateCode()
    {
        return strval(mt_rand(100000, 999999));
    }
}