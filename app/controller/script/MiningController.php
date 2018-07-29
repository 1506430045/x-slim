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
    const TASK_USER_NUM_PER_TIME = 200;     //每次处理用户数
    const TASK_SLEEP_TIME = 50000;          //微妙
    const MINING_NUM_PER_TIME = 6;          //每个时间每用户挖矿数量
    const CURRENCY_NUM_PER_TIME = 2;        //每个时间每用户挖矿总额

    //挖矿奖励记录生成时间 次日凌晨3点前不领取将失效
    const MINING_CREATE_TIME = [
        '05:00:00',
        '11:00:00',
        '18:00:00',
    ];

    /**
     * 随机分配
     *
     * @param float $total
     * @param int $num
     * @param float $min
     * @return array
     */
    private function randomNum(float $total, int $num, float $min = 0.01)
    {
        $result = [];
        for ($i = 1; $i < $num; $i++) {
            $safeTotal = ($total - ($num - $i) * $min) / ($num - $i);//随机安全上限
            $money = mt_rand($min * 100, $safeTotal * 100) / 100;
            $total = $total - $money;
            $result[] = $money;
        }
        $result[] = $total;
        return $result;
    }

    //任务
    public function task()
    {
        $maxId = 0;
        while (true) {
            $userList = PdoModel::getInstance(MysqlConfig::$baseConfig)->table('candy_user')->where('id', ">", $maxId)->limit(self::TASK_USER_NUM_PER_TIME)->getList(['id']);
            if (empty($userList)) {
                break;
            }
            $count = count($userList);
            $maxId = $userList[$count - 1]['id'];
            $userIds = array_column($userList, 'id');
            $this->createMiningRecord($userIds);
            usleep(self::TASK_SLEEP_TIME);
        }
    }

    /**
     * 创建用户每日挖矿数据
     *
     * @param array $userIds
     * @return int
     */
    public function createMiningRecord($userIds = [])
    {
        $sql = "INSERT INTO `candy_mining` (user_id, mining_status, currency_id, currency_name, currency_number, effective_time, dead_time) VALUES";

        //时间
        $date = date('Y-m-d');
        $nextDate = date('Y-m-d', time() + 86400);  //次日日期
        $deadTime = strtotime("{$nextDate} 03:00:00");         //次日凌晨3点失效 未领取失效时间戳

        //状态
        $miningStatus = MiningModel::MINING_STATUS_1;   //待领取

        //奖励
        $currencyId = 1;
        $currencyName = 'TB';

        foreach (self::MINING_CREATE_TIME as $time) {
            $effectiveTime = strtotime($date . ' ' . $time);    //挖矿生效时间
            $diffTime = $effectiveTime - time();
            if ($diffTime > 3600 || $diffTime < 0) {
                continue;
            }
            foreach ($userIds as $userId) {
                $randomNum = $this->randomNum(self::CURRENCY_NUM_PER_TIME, self::MINING_NUM_PER_TIME);
                foreach ($randomNum as $currencyNumber) {
                    $sql .= " ({$userId}, {$miningStatus}, {$currencyId}, '{$currencyName}', {$currencyNumber}, {$effectiveTime}, {$deadTime}),";
                }
            }
        }
        $sql = rtrim($sql, ',');
        return PdoModel::getInstance(MysqlConfig::$baseConfig)->table('candy_mining')->execute($sql);
    }
}