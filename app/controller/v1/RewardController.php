<?php
/**
 * 奖励
 *
 * User: forward
 * Date: 2018/7/18
 * Time: 下午3:39
 */

namespace App\controller\v1;


use App\model\mining\MiningModel;

class RewardController extends BaseController
{
    //糖果记录
    public function list()
    {
        //1、做任务奖励TB
        //2、邀请好友奖励TB
        //3、挖矿奖励TB
        $waitList = (new MiningModel())->getMiningList($this->userId);
//        exit(json_encode($waitList));
        $rewardList = [
            [
                'icon' => '',
                'reward_type' => '签到',
                'currency_name' => 'TB',
                'currency_number' => 0.001,
                'time' => '12分钟前'
            ],
            [
                'icon' => '',
                'reward_type' => '签到',
                'currency_name' => 'TB',
                'currency_number' => 0.001,
                'time' => '12分钟前'
            ],
        ];
        $waitList = [
            [
                'currency_name' => 'TB',
                'currency_number' => 0.1,
                'time' => 21
            ],
            [
                'currency_name' => 'TB',
                'currency_number' => 0.1,
                'time' => 21
            ],
            [
                'currency_name' => 'TB',
                'currency_number' => 0.1,
                'time' => 21
            ],
        ];
        $data = [
            'currency_name' => 'tb',
            'currency_number' => 121,
            'reward_list' => $rewardList,
            'wait_list' => $waitList
        ];
        $this->renderJson(0, 'success', $data);
    }

    //领取挖矿奖励
    public function reward()
    {
        $miningId = $this->getInt('id', 0);

        $re = (new MiningModel())->reward($this->userId, $miningId);
        if ($re) {
            $this->renderJson();
        } else {
            $this->renderJson(400, '奖励已经领取或已失效');
        }
    }
}