<?php
/**
 * 邀请码
 *
 * User: forward
 * Date: 2018/7/17
 * Time: 下午3:03
 */

namespace App\model\user;


use App\model\BaseModel;

class InviteModel extends BaseModel
{
    /**
     * 生成邀请码
     *
     * @param int $userId
     * @return string
     */
    public static function createInviteCode(int $userId)
    {
        static $sourceString = 'RNKH7YUALZ382OP1FMXEWTSGD54IJ9CBQ6V';
        $num = $userId;
        $code = '';
        while ($num > 0) {
            $mod = $num % 35;
            $num = ($num - $mod) / 35;
            $code = $sourceString[$mod] . $code;
        }
        if (empty($code[3])) {
            $code = str_pad($code, 4, '0', STR_PAD_LEFT);
        }
        return $code;
    }

    /**
     * 邀请码反推用户id
     *
     * @param $code
     * @return int
     */
    public static function decodeInviteCode($code = '')
    {
        if (empty($code)) {
            return 0;
        }
        static $sourceString = 'RNKH7YUALZ382OP1FMXEWTSGD54IJ9CBQ6V';
        if (strrpos($code, '0') !== false) {
            $code = substr($code, strrpos($code, '0') + 1);
        }
        $len = strlen($code);
        $code = strrev($code);
        $num = 0;
        for ($i = 0; $i < $len; $i++) {
            $num += strpos($sourceString, $code[$i]) * pow(35, $i);
        }
        return $num;
    }
}