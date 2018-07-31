<?php
/**
 * 首页相关
 *
 * User: forward
 * Date: 2018/7/28
 * Time: 下午5:55
 */

namespace App\controller\v1;


use App\model\asset\AssetModel;
use App\model\mining\MiningModel;
use App\model\reward\RewardModel;
use App\model\task\TaskModel;

class IndexController extends BaseController
{
    //已经获取奖励
    public function rewardList()
    {
        $id = $this->getInt('id', 0);
        $rewardList = (new RewardModel())->getRewardList($this->userId, $id);

        $count = count($rewardList);
        $data = [
            'id' => $rewardList[$count - 1]['id'] ?? 0,
            'reward_list' => $rewardList
        ];
        $this->renderJson(0, 'success', $data);
    }

    //总资产
    public function asset()
    {
        $tbAsset = (new AssetModel())->getUserTotalAsset($this->userId, 'TB');
        $task = (new TaskModel())->getTaskConfById(TaskModel::TASK_CONF_ID_4);
        $data = [
            'tb_total' => [
                'currency_name' => $tbAsset['currency_name'] ?? 'TB',
                'currency_number' => !empty($tbAsset['currency_number']) ? round($tbAsset['currency_number'], 6) : 0.0
            ],
            'invite_reward' => [
                'currency_name' => AssetModel::TB_NAME,
                'currency_number' => round($task['currency_number'], 6) ?? 0
            ]
        ];
        $this->renderJson(0, 'success', $data);
    }

    //待收取糖果
    public function candyList()
    {
        $list = (new MiningModel())->getMiningList($this->userId);

        $data = [
            'candy_list' => $list
        ];
        $this->renderJson(0, 'success', $data);
    }
}