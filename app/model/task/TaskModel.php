<?php
/**
 * 任务配置
 *
 * User: forward
 * Date: 2018/7/17
 * Time: 下午2:33
 */

namespace App\model\task;


use App\model\reward\RewardModel;
use App\model\task\mysql\TaskConfModel;
use App\model\task\mysql\TaskModel as MysqlTaskModel;
use App\model\task\mysql\TaskConfModel as MysqlTaskConfModel;
use App\model\user\redis\UserModel as RedisUserModel;
use App\model\user\UserModel;
use Util\CacheUtil;

class TaskModel
{
    const TASK_CONF_ID_1 = 1;   //每日登录
    const TASK_CONF_ID_2 = 2;   //七日登录
    const TASK_CONF_ID_3 = 3;   //绑定手机
    const TASK_CONF_ID_4 = 4;   //邀请好友

    const TASK_STATUS_0 = 0;    //未完成
    const TASK_STATUS_1 = 1;    //已完成
    const TASK_STATUS_2 = 2;    //持续完成

    /**
     * 获取任务列表
     *
     * @return array
     */
    public function getTaskConfList()
    {
        $cacheKey = 'get:task:conf:list';
        if ($data = CacheUtil::getCache($cacheKey)) {
            return $data;
        }
        $taskList = (new mysql\TaskConfModel())->getTaskConfList();
        if (empty($taskList)) {
            return [];
        }
        $normalList = $advancedList = [];
        foreach ($taskList as $v) {
            if ($v['task_type'] === mysql\TaskModel::TASK_TYPE_1) {
                $normalList[] = $v;
            }
            if ($v['task_type'] === mysql\TaskModel::TASK_TYPE_2) {
                $advancedList[] = $v;
            }
        }
        $data = [
            $normalList,
            $advancedList
        ];
        CacheUtil::setCache($cacheKey, $data, 1800);
        return $data;
    }

    /**
     * 获取任务状态
     *
     * @param int $userId
     * @param string $openId
     * @param array $taskList
     * @return array
     */
    public function getTaskStatus(int $userId, string $openId, array $taskList = [])
    {
        if (empty($openId) || empty($taskList)) {
            return [];
        }
        foreach ($taskList as &$v) {
            $v['ext'] = [];
            if (self::TASK_CONF_ID_1 === $v['id']) {       //每日登录默认完成
                $v['task_status'] = self::TASK_STATUS_1;
            }
            if (self::TASK_CONF_ID_2 === $v['id']) {       //七日登录
                $v['task_status'] = self::TASK_STATUS_0;
                $v['ext']['sign_times'] = (new RedisUserModel)->getUserPerWeekSignTimes($userId);
            }
            if (self::TASK_CONF_ID_3 === $v['id']) {       //绑定手机
                $user = (new UserModel())->getUserByOpenId($openId);
                $v['task_status'] = !empty($user['phone']) ? self::TASK_STATUS_1 : self::TASK_STATUS_0;
            }
            if (self::TASK_CONF_ID_4 === $v['id']) {       //持续完成
                $v['task_status'] = self::TASK_STATUS_2;
            }
        }
        return $taskList;
    }

    /**
     * 获取任务配置详情
     *
     * @param int $id
     * @return array
     */
    public function getTaskConfById(int $id)
    {
        $cacheKey = sprintf('get:task:conf:list:%d', $id);
        if ($data = CacheUtil::getCache($cacheKey)) {
            return $data;
        }
        $data = (new MysqlTaskConfModel())->getTaskConfById($id);
        if (empty($data)) {
            return [];
        }
        CacheUtil::setCache($cacheKey, $data, 1800);
        return $data;
    }

    /**
     * 签到生成任务及奖励
     *
     * @param int $userId
     * @return int
     */
    public function createSignInTask(int $userId)
    {
        $flag = (new RedisUserModel())->setUserSignFlag($userId);
        if ($flag !== 1) {  //已签到或异常
            return 0;
        }
        $taskConf = $this->getTaskConfById(TaskConfModel::TASK_CONF_ID_1);
        $id = (new MysqlTaskModel)->signIn($userId, $taskConf);
        if ($id) {
            $times = (new RedisUserModel)->setUserPerWeekSignTimes($userId); //记录用户当前周签到次数
            if ($times === '1111111') { //7日签到生成任务及奖励
                $this->createSignIn7Task($userId);
            }
            $currency = [
                'currency_id' => $taskConf['currency_id'],
                'currency_name' => $taskConf['currency_name'],
                'currency_number' => $taskConf['currency_number']
            ];
            (new RewardModel())->createRewardRecord($userId, RewardModel::REWARD_TYPE_1, $id, $currency, '每日登录');
        }
        return $id;
    }

    /**
     * 7日签到生成任务及奖励
     *
     * @param int $userId
     * @return int
     */
    public function createSignIn7Task(int $userId)
    {
        $taskConf = $this->getTaskConfById(TaskConfModel::TASK_CONF_ID_2);
        $id = (new MysqlTaskModel)->signIn($userId, $taskConf);
        if ($id) {
            $currency = [
                'currency_id' => $taskConf['currency_id'],
                'currency_name' => $taskConf['currency_name'],
                'currency_number' => $taskConf['currency_number']
            ];
            (new RewardModel())->createRewardRecord($userId, RewardModel::REWARD_TYPE_1, $id, $currency, '7日登录');
        }
        return $id;
    }

    /**
     * 绑定手机生成任务
     *
     * @param int $userId
     * @return int
     */
    public function createBindPhoneTask(int $userId)
    {
        $taskConf = $this->getTaskConfById(TaskConfModel::TASK_CONF_ID_3);
        $id = (new MysqlTaskModel)->bindPhone($userId, $taskConf);
        if ($id) {
            $currency = [
                'currency_id' => $taskConf['currency_id'],
                'currency_name' => $taskConf['currency_name'],
                'currency_number' => $taskConf['currency_number']
            ];
            (new RewardModel())->createRewardRecord($userId, RewardModel::REWARD_TYPE_1, $id, $currency, '绑定手机');
        }
        return $id;
    }
}