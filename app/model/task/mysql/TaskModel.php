<?php
/**
 * 任务
 *
 * User: forward
 * Date: 2018/7/19
 * Time: 下午6:03
 */

namespace App\model\task\mysql;


use App\model\PdoModel;
use Config\db\MysqlConfig;

class TaskModel
{
    const TASK_TYPE_1 = 1;  //普通任务
    const TASK_TYPE_2 = 2;  //高级任务

    const TASK_STATUS_0 = 0;    //未完成
    const TASK_STATUS_1 = 1;    //已完成

    private $table;

    public function __construct()
    {
        $this->table = 'candy_task';
    }

    /**
     * 签到任务
     *
     * @param $userId
     * @param array $taskConf
     * @return int
     */
    public function signIn($userId, array $taskConf = [])
    {
        if (empty($userId) || empty($taskConf)) {
            return 0;
        }
        $data = [
            'user_id' => $userId,
            'conf_id' => $taskConf['id'],
            'task_type' => $taskConf['task_type'],
            'task_status' => self::TASK_STATUS_1,
            'currency_name' => $taskConf['currency_name'],
            'currency_number' => $taskConf['currency_number'],
            'created_date' => date('Y-m-d', $_SERVER['REQUEST_TIME']),
            'start_time' => $_SERVER['REQUEST_TIME'],
            'finish_time' => $_SERVER['REQUEST_TIME']
        ];
        try {
            return PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)->insert($data);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * 绑定手机任务
     *
     * @param int $userId
     * @param array $taskConf
     * @return int
     */
    public function bindPhone(int $userId, array $taskConf = [])
    {
        if (empty($userId) || empty($taskConf)) {
            return 0;
        }
        $data = [
            'user_id' => $userId,
            'conf_id' => $taskConf['id'],
            'task_type' => $taskConf['task_type'],
            'task_status' => self::TASK_STATUS_1,
            'currency_id' => $taskConf['currency_id'],
            'currency_name' => $taskConf['currency_name'],
            'currency_number' => $taskConf['currency_number'],
            'created_date' => date('Y-m-d', $_SERVER['REQUEST_TIME']),
            'start_time' => $_SERVER['REQUEST_TIME'],
            'finish_time' => $_SERVER['REQUEST_TIME']
        ];
        try {
            return PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)->insert($data);
        } catch (\Exception $e) {
            return 0;
        }
    }
}