<?php
/**
 * 对接微信相关接口
 *
 * User: forward
 * Date: 2018/7/17
 * Time: 下午4:28
 */

namespace App\model\wechat;


use App\model\BaseModel;
use Util\GuzzleUtil;

class WechatModel extends BaseModel
{
    //测试
    const APP_ID = 'wx206473ae20f169bb';
    const APP_SECRET = '134c38c7709ebccbf4821eb7497d1d8d';
    //线上
//    const APP_ID = 'wxfb2b7642d9c173d7';
//    const APP_SECRET = 'c23e66dac468af88e0a0c04262a1c6ed';
    const GRANT_TYPE = 'authorization_code';
    const BASE_URL = 'https://api.weixin.qq.com';

    /**
     * 加密数据匹配
     *
     * @param $sessionKey
     * @param $encryptedData
     * @param $iv
     * @param $openId
     * @return array
     */
    public static function encryptDataMatch($sessionKey, $encryptedData, $iv, $openId)
    {
        $pc = new WXBizDataCrypt(self::APP_ID, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);

        $status = $errCode == 0;
        if (empty($data['openId']) || $openId !== $data['openId']) {
            $status = false;
        }
        return [
            'status' => $status,
            'err_code' => $errCode,
            'data' => $data
        ];
    }

    /**
     * 获取openid和session_key
     *
     * @param string $code
     * @return array
     */
    public static function getOpenId($code = '')
    {
        if (empty($code)) {
            return [
                'status' => false,
                'message' => 'code不能为空'
            ];
        }
        $params = [
            'appid' => self::APP_ID,
            'secret' => self::APP_SECRET,
            'js_code' => $code,
            'grant_type' => self::GRANT_TYPE
        ];
        $ret = GuzzleUtil::getClient(self::BASE_URL)->get('/sns/jscode2session', $params);
        if (empty($ret['status'])) {
            return [
                'status' => false,
                'message' => '服务异常，请稍后再试'
            ];
        }
        return [
            'status' => true,
            'message' => 'success',
            'data' => $ret['data']
        ];
    }

    /**
     * 设置3rd_session
     *
     * @param $openid
     * @param $sessionKey
     * @return bool
     */
    public static function set3rdSession($openid, $sessionKey)
    {
        $token = self::get3rdSessionKey(64);
        (new Redis\WechatModel())->set3rdSession($token, sprintf("%s %s", $openid, $sessionKey));
        return $token;
    }


    /**
     * 生成3rd_session key
     *
     * @param int $len
     * @return string
     */
    private static function get3rdSessionKey($len = 168)
    {
        $fp = @fopen('/dev/urandom', 'rb');
        $result = '';
        if ($fp !== FALSE) {
            $result .= @fread($fp, $len);
            @fclose($fp);
        } else {
            trigger_error('Can not open /dev/urandom.');
        }
        //convert from binary to string
        $result = base64_encode($result);
        //remove none url chars
        $result = strtr($result, '+/', '-_');
        return substr($result, 0, $len) ?: '';
    }
}