<?php
/**
 * 任务配置
 *
 * User: forward
 * Date: 2018/7/17
 * Time: 下午2:33
 */

namespace App\model\task;


use App\model\task\mysql\TaskConfModel;
use App\model\task\mysql\TaskModel as MysqlTaskModel;
use App\model\user\redis\UserModel as RedisUserModel;
use App\model\user\UserModel;
use Util\CacheUtil;

class TaskModel
{
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
        $data = (new mysql\TaskConfModel())->getTaskConfById($id);
        if (empty($data)) {
            return [];
        }
        CacheUtil::setCache($cacheKey, $data, 1800);
        return $data;
    }

    /**
     * 签到
     *
     * @param string $openId
     * @param int $userId
     * @return int
     */
    public function createSignInTask(string $openId, int $userId)
    {
        $flag = (new RedisUserModel())->setUserSignFlag($userId);
        if ($flag !== 1) {  //已签到或异常
            return 0;
        }
        $userInfo = (new UserModel)->getUserByOpenId($openId);
        if (empty($userInfo['id'])) {
            return 0;
        }
        $taskConf = $this->getTaskConfById(TaskConfModel::TASK_CONF_ID_1);
        return (new MysqlTaskModel)->signIn($userInfo['id'], $taskConf);
    }
}