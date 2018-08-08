<?php
/**
 * Created by PhpStorm.
 * User: forward
 * Date: 2018/8/9
 * Time: 上午1:02
 */

namespace App\controller\script;


use App\model\PdoModel;
use App\model\reward\RewardModel;
use App\model\invite\InviteModel;
use Config\db\MysqlConfig;

class InviteFixController
{
    public function fix()
    {
        //所有可能异常数据
        $a = [4, 11, 15, 17, 47, 55, 66, 82, 89, 97, 111, 117, 157, 163, 182, 188, 200, 202, 207, 216, 226, 254, 256, 262, 267, 269, 284, 326, 341, 343, 356, 386, 391, 397, 399, 403, 410, 419, 449, 457, 485, 491, 494, 502, 523, 543, 545, 549, 551, 557, 566, 569, 574, 582, 594, 606, 614];
        //已领取奖励数据
        $b = [66, 55, 117, 157, 111, 182, 256, 267, 269, 284, 386, 391, 397, 410, 419, 341, 449, 89, 82, 485, 494, 502, 523, 543, 545, 549, 557, 566, 569, 574, 582, 594, 4, 606, 614];

        $diff = array_diff($a, $b);
        $currency = [
            'currency_id' => 1,
            'currency_name' => 'TB',
            'currency_number' => 100
        ];
        $invitePdo = PdoModel::getInstance(MysqlConfig::$baseConfig);
        $userPdo = PdoModel::getInstance(MysqlConfig::$baseConfig);
        foreach ($diff as $fid) {
            $row = $invitePdo->table('candy_invite')->where('id', '=', $fid)->getRow(['id', 'inviter', 'invitee']);
            if (empty($row)) {
                continue;
            }
            $user = $userPdo->table('candy_user')->where('id', '=', $row['invitee'])->getRow(['id', 'nickname']);
            if (empty($user)) {
                continue;
            }
            $re = (new RewardModel())->createRewardRecord($row['inviter'], RewardModel::REWARD_TYPE_3, $fid, $currency, '邀请' . $user['nickname']);
            if ($re) {
                echo sprintf("%s,%s,%s,%s,成功\n", $fid, $row['inviter'], $row['invitee'], $user['nickname']);
            } else {
                echo sprintf("%s,%s,%s,%s,失败\n", $fid, $row['inviter'], $row['invitee'], $user['nickname']);
            }
        }
    }
}