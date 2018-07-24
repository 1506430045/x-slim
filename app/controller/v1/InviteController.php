<?php
/**
 * 邀请
 *
 * User: forward
 * Date: 2018/7/17
 * Time: 下午2:57
 */

namespace App\controller\v1;


use App\model\task\TaskModel;
use App\model\user\InviteModel as UserInviteModel;
use App\model\user\UserModel;
use App\model\invite\InviteModel;

class InviteController extends BaseController
{
    //获取邀请码
    public function inviteCode()
    {
        $inviteCode = UserInviteModel::createInviteCode($this->userId);

        (new UserModel())->bindInviteCode($this->userId, $this->openId, $inviteCode);

        $data = [
            'invite_code' => $inviteCode
        ];
        $this->renderJson(0, 'success', $data);
    }

    //邀请列表
    public function list()
    {
        $inviteList = (new InviteModel())->getInviteList($this->userId);
        $data = [
            'invite_people' => count($inviteList),
            'invite_reward' => array_sum(array_column($inviteList, 'currency_number')),
            'invite_list' => $inviteList
        ];
        $this->renderJson(0, 'success', $data);
    }
}