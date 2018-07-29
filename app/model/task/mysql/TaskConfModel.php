<?php
/**
 * 任务配置
 *
 * User: forward
 * Date: 2018/7/19
 * Time: 下午4:07
 */

namespace App\model\task\mysql;


use App\model\PdoModel;
use Config\db\MysqlConfig;
use Util\LoggerUtil;

class TaskConfModel
{
    const TASK_CONF_ID_1 = 1;   //签到 与数据库保持一致
    const TASK_CONF_ID_2 = 2;   //连续7天签到 与数据库保持一致
    const TASK_CONF_ID_3 = 3;   //绑定手机 与数据库保持一致
    const TASK_CONF_ID_4 = 4;   //邀请用户 与数据库保持一致

    private $table;

    public function __construct()
    {
        $this->table = 'candy_task_conf';
    }

    /**
     * 获取任务配置列表
     *
     * @param int $taskType 0全任务 1普通任务 or 2高级任务
     * @return array
     */
    public function getTaskConfList(int $taskType = 0)
    {
        try {
            $task = PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table);
            if ($taskType !== 0) {
                $task->where('task_type', '=', $taskType);
            }
            return $task->order('task_sort ASC')->getList(['id', 'task_name', 'task_description', 'task_type', 'currency_name', 'currency_number']);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * 获取任务配置详情
     *
     * @param int $id
     * @return array
     */
    public function getTaskConfById(int $id)
    {
        try {
            $task = PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)->where('id', '=', $id)->getRow();
            return $task;
        } catch (\Exception $e) {
            return [];
        }
    }
}