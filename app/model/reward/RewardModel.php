<?php
/**
 * 奖励
 *
 * User: forward
 * Date: 2018/7/23
 * Time: 下午7:43
 */

namespace App\model\reward;


use App\model\BaseModel;
use App\model\PdoModel;
use Config\db\MysqlConfig;

class RewardModel extends BaseModel
{
    const REWARD_TYPE_1 = 1;    //任务奖励
    const REWARD_TYPE_2 = 2;    //挖矿奖励
    const REWARD_TYPE_3 = 3;    //邀请奖励

    private $table;

    public function __construct()
    {
        $this->table = 'candy_reward';
    }

    /**
     * 创建奖励记录
     *
     * @param $userId
     * @param int $rewardType
     * @param int $foreignId
     * @param array $currency
     * @param string $description
     * @return bool
     */
    public function createRewardRecord($userId, int $rewardType, int $foreignId, array $currency, $description = '')
    {
        $sql1 = "INSERT INTO `{$this->table}` (user_id, reward_type, foreign_id, currency_id, currency_name, currency_number, reward_description) VALUES ({$userId}, {$rewardType}, {$foreignId}, {$currency['currency_id']}, '{$currency['currency_name']}', '{$description}')";
        $sql2 = "UPDATE `candy_asset` SET currency_number = currency_number + {$currency['currency_number']} WHERE user_id = {$userId} AND currency_id = {$currency['currency_id']} LIMIT 1";

        $sql = [
            $sql1,
            $sql2
        ];
        return PdoModel::getInstance(MysqlConfig::$baseConfig)->executeTransaction($sql);
    }

    /**
     * 获取奖励列表
     *
     * @param int $userId
     * @return array
     */
    public function getRewardList(int $userId)
    {
        try {
            $list = PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)
                ->where('user_id', '=', $userId)
                ->order('id desc')
                ->getList(['id', 'reward_type', 'foreign_id', 'currency_name', 'currency_number', 'created_at']);
            if (empty($list)) {
                return [];
            }
            $rewardTypeMap = $this->formatRewardType($list);
            foreach ($list as &$v) {
                $id = $v['id'];
                $v['reward_type'] = $rewardTypeMap[$id] ?? '任务奖励';
                unset($v['id']);
            }
            return $list;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * 格式化奖励类型
     *
     * @param array $rewardList
     * @return array
     */
    private function formatRewardType(array $rewardList = [])
    {
        if (empty($rewardList)) {
            return [];
        }
        $result = [];
        foreach ($rewardList as $v) {
            $id = $v['id'];
            $rewardType = $v['reward_type'];
            $rewardTypeStr = '任务奖励';
            if ($rewardType === self::REWARD_TYPE_2) {
                $rewardTypeStr = '日常领取';
            }
            if ($rewardType === self::REWARD_TYPE_3) {
                $rewardTypeStr = '邀请XX';
            }
            if ($rewardType === self::REWARD_TYPE_1) {
                $rewardTypeStr = '任务';
            }
            $result[$id] = $rewardTypeStr;
        }
        return $result;
    }
}