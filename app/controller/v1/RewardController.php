<?php
/**
 * 奖励
 *
 * User: forward
 * Date: 2018/7/18
 * Time: 下午3:39
 */

namespace App\controller\v1;


use App\model\asset\AssetModel;
use App\model\mining\MiningModel;
use App\model\reward\RewardModel;

class RewardController extends BaseController
{
    //糖果记录
    public function list()
    {
        $id = $this->getInt('id', 0);

        $waitList = (new MiningModel())->getMiningList($this->userId);
        $rewardList = (new RewardModel())->getRewardList($this->userId, $id);
        $tbAsset = (new AssetModel())->getUserTotalAsset($this->userId, 'TB');
        $data = [
            'currency_name' => $tbAsset['currency_name'] ?? AssetModel::TB_NAME,
            'currency_number' => !empty($tbAsset['currency_number']) ? floatval($tbAsset['currency_number']) : 0.0,
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