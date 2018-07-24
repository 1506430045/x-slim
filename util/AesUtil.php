<?php
/**
 * Aes对称加密解密
 *
 * User: forward
 * Date: 2018/6/25
 * Time: 下午9:40
 */

namespace Util;

use PhpAes\Aes;

class AesUtil
{
    const SECRET_KEY = 'zJMwvJtwSHsSCsdD';  //秘钥
    const IV = 'abcdef1234567890';          //初始化向量

    /**
     * 加密
     *
     * @param $keyword
     * @return string
     */
    public static function encrypt($keyword)
    {
        $aes = new Aes(self::SECRET_KEY, 'CBC', self::IV);
        return bin2hex($aes->encrypt($keyword)); //给二进制转为16进制，所谓的解决乱码
    }

    /**
     * 解密
     *
     * @param $cipher
     * @return string
     */
    public static function decrypt($cipher)
    {
        $cipher = hex2bin($cipher);
        $aes = new Aes(self::SECRET_KEY, 'CBC', self::IV);
        return $aes->decrypt($cipher);
    }

    /**
     * 随机生成字符串
     *
     * @param int $length
     * @return string
     */
    public static function generatePassword($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $password;
    }
}