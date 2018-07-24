<?php
/**
 * Created by PhpStorm.
 * User: forward
 * Date: 2018/7/18
 * Time: 下午8:32
 */

namespace Util\sms;


use Util\GuzzleUtil;

class Twilio
{
    const FROM = '+18317039998';
    const AUTHORIZATION = 'Basic QUM4NDM1ZDg0YmQxYjRhMzc5MDBkZjdiNWZhZDNhYjIzZTo4OTc2NjcxYjU0Njc3MWNhZmE0YTQ1NzY1MTg1ZTQyZA==';
    const BASE_URL = 'https://api.twilio.com';

    /**
     * 发送短信
     *
     * @param $mobile
     * @param $content
     * @return array
     */
    public static function send($mobile, $content)
    {
        $header = [
            'Authorization' => self::AUTHORIZATION
        ];
        $params = [
            'From' => self::FROM,
            'To' => $mobile,
            'Body' => $content
        ];
        return GuzzleUtil::getClient(self::BASE_URL)->post('/2010-04-01/Accounts/AC8435d84bd1b4a37900df7b5fad3ab23e/Messages.json', $params, $header);
    }
}