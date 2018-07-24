<?php
/**
 * Created by PhpStorm.
 * User: forward
 * Date: 2018/7/18
 * Time: 下午7:07
 */

namespace Util\sms;


use Util\GuzzleUtil;

class Yunpian
{
    const API_KEY = 'f05cf8f423cee2a5dfdfef2f738c5fa0';
    const BASE_URL = 'https://sms.yunpian.com';

    /**
     * 发送短信
     *
     * @param $regionId
     * @param $phone
     * @param $content
     * @return array
     */
    public static function send($regionId, $phone, $content)
    {
        $mobile = $phone;
        if ($regionId != 86) {
            $mobile = "%2B" . $regionId + $phone;
        }

        $params = [
            'apikey' => self::API_KEY,
            'text' => $content,
            'mobile' => $mobile
        ];
        return GuzzleUtil::getClient(self::BASE_URL)->post('/v2/sms/single_send.json', $params);
    }
}