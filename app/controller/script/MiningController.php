<?php
/**
 * 生成挖矿奖励脚本
 *
 * User: forward
 * Date: 2018/7/18
 * Time: 下午4:31
 */

namespace App\controller\script;


use App\model\mining\MiningModel;
use App\model\PdoModel;
use Config\db\MysqlConfig;

class MiningController
{
    const USER_MINING_NUM_PER_DAY = 10;     //每个用户每天TB挖矿总量
    const TASK_USER_NUM_PER_TIME = 200;     //每次处理用户数
    const TASK_SLEEP_TIME = 50000;

    //挖矿奖励记录生成时间 24小时不领取将失效
    const MINING_CREATE_TIME = [
        '01:00:00',
        '05:00:00',
        '09:00:00',
        '13:00:00',
        '17:00:00',
        '21:00:00',
    ];

    //任务
    public function task()
    {
        $maxId = 0;
        $currency = [
            'currency_id' => 1,
            'currency_name' => 'TB',
            'currency_number' => round(self::USER_MINING_NUM_PER_DAY / count(self::MINING_CREATE_TIME), 18)
        ];
        while (true) {
            $userList = PdoModel::getInstance(MysqlConfig::$baseConfig)->table('candy_user')->where('id', ">", $maxId)->limit(self::TASK_USER_NUM_PER_TIME)->getList(['id']);
            if (empty($userList)) {
                break;
            }
            $count = count($userList);
            $maxId = $userList[$count - 1]['id'];
            $userIds = array_column($userList, 'id');
            $this->createMiningRecord($userIds, $currency);
            usleep(self::TASK_SLEEP_TIME);
        }
    }

    /**
     * 创建用户每日挖矿数据
     *
     * @param array $userIds
     * @param array $currency
     * @return int
     */
    public function createMiningRecord($userIds = [], $currency = [])
    {
        $sql = "INSERT INTO `candy_mining` (user_id, mining_status, currency_id, currency_name, currency_number, effective_time, dead_time) VALUES";
        $date = date('Y-m-d');
        $miningStatus = MiningModel::MINING_STATUS_1;   //待领取
        foreach ($userIds as $userId) {
            foreach (self::MINING_CREATE_TIME as $time) {
                $effectiveTime = strtotime($date . ' ' . $time);
                $deadTime = strtotime($date . ' ' . $time) + 86400; //生效起24小时没领取则失效
                $sql .= " ({$userId}, {$miningStatus}, {$currency['currency_id']}, '{$currency['currency_name']}', {$currency['currency_number']}, {$effectiveTime}, {$deadTime}),";
            }
        }
        $sql = rtrim($sql, ',');
        return PdoModel::getInstance(MysqlConfig::$baseConfig)->table('candy_mining')->execute($sql);
    }
}