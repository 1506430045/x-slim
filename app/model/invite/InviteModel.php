<?php
/**
 * 邀请
 *
 * User: forward
 * Date: 2018/7/24
 * Time: 上午12:05
 */

namespace App\model\invite;


use App\model\BaseModel;
use App\model\PdoModel;
use App\model\reward\RewardModel;
use App\model\task\mysql\TaskConfModel;
use App\model\user\UserModel;
use Config\db\MysqlConfig;

class InviteModel extends BaseModel
{
    const INVITE_STATUS_0 = 0;  //邀请状态  0待被邀请人绑定手机
    const INVITE_STATUS_1 = 1;  //邀请状态  1被邀请人已绑定手机

    private $table;

    public function __construct()
    {
        $this->table = 'candy_invite';
    }

    /**
     * 插入邀请记录
     *
     * @param int $inviter
     * @param int $invitee
     * @return int
     */
    public function add(int $inviter, int $invitee)
    {
        $currency = (new TaskConfModel())->getTaskConfById(TaskConfModel::TASK_CONF_ID_4);
        $data = [
            'inviter' => $inviter,
            'invitee' => $invitee,
            'currency_id' => $currency['currency_id'],
            'currency_name' => $currency['currency_name'],
            'currency_number' => $currency['currency_number']
        ];
        try {
            return PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)->insert($data);
        } catch
        (\Exception $e) {
            return 0;
        }
    }

    /**
     * 创建邀请奖励
     *
     * @param $inviter
     * @param $invitee
     * @param string $description
     * @return int
     */
    public function inviteReward($inviter, $invitee, $description = '')
    {
        try {
            $row = PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)
                ->where('invitee', '=', $invitee)
                ->where('inviter', '=', $inviter)
                ->getRow(['currency_id', 'currency_name', 'currency_number', 'invite_status']);
            if (empty($row) || $row['invite_status'] === self::INVITE_STATUS_1) {
                return 0;
            }
        } catch (\Exception $e) {
            return 0;
        }

        $data = [
            'invite_status' => self::INVITE_STATUS_1
        ];
        try {
            $re = PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)
                ->where('inviter', '=', $inviter)
                ->where('invitee', '=', $invitee)
                ->where('invitee_status', '=', self::INVITE_STATUS_0)
                ->update($data);
            if ($re) {
                (new RewardModel)->createRewardRecord($inviter, RewardModel::REWARD_TYPE_3, $row, $description);
            }
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * 获取邀请列表
     *
     * @param int $inviter
     * @return array
     */
    public function getInviteList(int $inviter)
    {
        try {
            $list = PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)
                ->where('inviter', '=', $inviter)
                ->getList(['invitee', 'currency_name', 'currency_number', 'invite_status', 'created_at']);
            if (empty($list)) {
                return [];
            }
            $userIds = array_column($list, 'invitee');
            $userList = (new UserModel)->getUsersByUserIds($userIds);
            foreach ($list as &$v) {
                $userId = $v['invitee'];
                $v['nickname'] = $userList[$userId]['nickname'] ?? '';
                $v['avatar_url'] = $userList[$userId]['avatar_url'] ?? '';
            }
            return $list;
        } catch (\Exception $e) {
            return [];
        }
    }
}