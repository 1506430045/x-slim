<?php
/**
 * 任务
 *
 * User: forward
 * Date: 2018/7/17
 * Time: 下午2:31
 */

namespace App\controller\v1;


use App\model\task\TaskModel;
use App\model\user\UserModel;

class TaskController extends BaseController
{
    //任务列表
    public function list()
    {
        $taskModel = new TaskModel();
        list($normalList, $advancedList) = $taskModel->getTaskConfList();

        $data = [
            'normal_number' => count($normalList),
            'advanced_number' => count($advancedList),
            'normal_list' => $taskModel->getTaskStatus($this->userId, $this->openId, $normalList),
            'advanced_list' => $advancedList
        ];
        $this->renderJson(0, 'success', $data);
    }
}