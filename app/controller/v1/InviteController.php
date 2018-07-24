<?php
/**
 * 邀请
 *
 * User: forward
 * Date: 2018/7/17
 * Time: 下午2:57
 */

namespace App\controller\v1;


use App\model\asset\AssetModel;
use App\model\user\InviteModel;
use App\model\user\UserModel;

class InviteController extends BaseController
{
    //获取邀请码
    public function inviteCode()
    {
        $inviteCode = InviteModel::createInviteCode($this->userId);

        (new UserModel())->bindInviteCode($this->userId, $this->openId, $inviteCode);

        $data = [
            'invite_code' => $inviteCode
        ];
        $this->renderJson(0, 'success', $data);
    }

    //邀请列表
    public function list()
    {
        $inviteList = (new UserModel())->getInviteList($this->userId);
        exit(json_encode($inviteList));
        $data = [
            'invite_people' => 11,
            'invite_reward' => 10,
            'invite_list' => [
                [
                    'created_at' => date('Y.m.d H:i'),
                    'currency_name' => 'TB',
                    'currency_number' => 101.1,
                    'friend_name' => 'xxixi'
                ],
                [
                    'created_at' => date('Y.m.d H:i'),
                    'currency_name' => 'TB',
                    'currency_number' => 101.1,
                    'friend_name' => 'xxixi'
                ],
                [
                    'created_at' => date('Y.m.d H:i'),
                    'currency_name' => 'TB',
                    'currency_number' => 101.1,
                    'friend_name' => 'xxixi'
                ]
            ]
        ];
        $this->renderJson(0, 'success', $data);
    }
}