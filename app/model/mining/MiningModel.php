<?php
/**
 * 挖矿
 *
 * User: forward
 * Date: 2018/7/23
 * Time: 下午3:24
 */

namespace App\model\mining;


use App\model\BaseModel;
use App\model\PdoModel;
use App\model\reward\RewardModel;
use Config\db\MysqlConfig;
use Util\LoggerUtil;

class MiningModel extends BaseModel
{
    const MINING_STATUS_1 = 1;  //待领取
    const MINING_STATUS_2 = 2;  //已领取

    private $table;

    public function __construct()
    {
        $this->table = 'candy_mining';
    }

    /**
     * 获取待领取挖矿奖励
     *
     * @param int $userId
     * @return array
     */
    public function getMiningList(int $userId)
    {
        $now = time();
        try {
            $pdo = PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table);
            //条件 1、未领取 2、未过期
            $list = $pdo->where('user_id', '=', $userId)
                ->where('mining_status', '=', self::MINING_STATUS_1)
//                ->where('effective_time', '<=', $now)
                ->where('dead_time', '>', $now)
                ->getList(['id', 'currency_name', 'currency_number', 'effective_time']);
            if (empty($list)) {
                return [];
            }
            foreach ($list as &$v) {
                $v['effective_time'] = date('Y-m-d H:i:s');
            }
            return $list;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * 领取奖励
     *
     * @param int $userId
     * @param int $miningId
     * @return int
     * @throws \Exception
     */
    public function reward(int $userId, int $miningId)
    {
        $now = time();
        $pdo = PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table);
        $row = $pdo->where('id', '=', $miningId)->getRow(['user_id', 'mining_status', 'effective_time', 'dead_time', 'currency_id', 'currency_name', 'currency_number']);
        if (empty($row)) {
            return 0;
        }
        if ($row['mining_status'] === self::MINING_STATUS_2) {
            return 0;
        }
        if ($row['effective_time'] > $now) {
            return 0;
        }
        if ($row['dead_time'] <= $now) {
            return 0;
        }
        $update = [
            'mining_status' => self::MINING_STATUS_2
        ];
        try {
            $re = $pdo
                ->where('id', '=', $miningId)//挖矿ID
                ->where('user_id', '=', $userId)//用户ID
                ->where('mining_status', '=', 1)//未领取
                ->where('effective_time', '<=', $now)//已生效
                ->where('dead_time', '>', $now)//未过期
                ->update($update);
            if ($re) {
                $currency = [
                    'currency_id' => $row['currency_id'],
                    'currency_name' => $row['currency_name'],
                    'currency_number' => $row['currency_number']
                ];
                $create = (new RewardModel())->createRewardRecord($userId, RewardModel::REWARD_TYPE_2, $miningId, $currency);
                if (!$create) {
                    LoggerUtil::getInstance()->warning(sprintf('创建奖励记录失败，%s, $s', __METHOD__, json_encode(func_get_args())));
                }
            }
            return $re;
        } catch (\Exception $e) {
            return 0;
        }
    }
}