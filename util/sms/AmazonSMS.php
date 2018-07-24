<?php
/**
 * Created by PhpStorm.
 * User: forward
 * Date: 2018/7/18
 * Time: 下午8:39
 */

namespace Util\sms;


use Aws\S3\S3Client;
use Aws\Sns\SnsClient;
use Util\LoggerUtil;

class AmazonSMS
{
    const KEY = 'AKIAIW6SAOQ5DNJDDMWA';
    const SECRET = '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';

    /**
     * 发送短信
     *
     * @param $mobile
     * @param $content
     */
    public static function send($mobile, $content)
    {
        $snsClient = new SnsClient([
            'region' => 'ap-northeast-1',//这是亚马逊在新加坡的服务器，具体要根据情况决定 //ap-southeast-1
            'credentials' => [
                'key' => self::KEY,
                'secret' => self::SECRET,
            ],
            'version' => 'latest',    //一般在aws的官方api中会有关于这个插件的版本信息
            'debug' => false,
        ]);
        $snsClient->createTopic([
            'Name' => 'candy_sms'
        ]);
        $args = [
            'Message' => $content,
            'PhoneNumber' => $mobile,
        ];
        $snsClient->Publish($args);
    }
}