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
     * @return int
     */
    public function inviteReward($inviter, $invitee)
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
                (new RewardModel)->createRewardRecord($inviter, self::REWARD_TYPE_3, $row);
            }
        } catch (\Exception $e) {
            return 0;
        }
    }
}