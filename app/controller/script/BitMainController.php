<?php
/**
 * Created by PhpStorm.
 * User: forward
 * Date: 2018/8/20
 * Time: 下午6:40
 */

namespace App\controller\script;


use App\model\PdoModel;
use App\model\reward\RewardModel;
use App\model\task\TaskModel;
use Config\db\MysqlConfig;
use Util\AesUtil;

class BitMainController extends BaseController
{
    public function run()
    {
        $str = '15726511343,15928576794,18565876689,18037227510,18608303295,15021881882,13793058225,15051860766,13720932090,13320718442,15137831732,13155568400,17771428490,13215006737,17602004746,13737078014,13541711898,18846445746,18906938430,18557547928,18759172319,13106271384,13132917626,13880471590,18122984299,15692373238,13837772459,18521943780,13819940939,15356855023,17040498004,13286612855,13164654656,15168280601,18506850717,13682358380,15637738759,18223046125,17633951190,15927437779,18580585658,13596148143,17872159001,15624321188,13982259349,13237323973,13359958866,13115379471,18003376855,15154624326,13938233554,13594133966,13533402351,15359885815,18303950009,15651793872,13866850989,13350859338,13370539987,13837772459,18956291497,18269060220,15179484670,17040498004,13760494686,15179484670,13737078014';
        $arr = explode(',', $str);
        $currency = [
            'currency_id' => 1,
            'currency_name' => 'TB',
            'currency_number' => 50
        ];
        $rewardModel = new RewardModel();
        foreach ($arr as $phone) {
            $phoneEncrypt = trim($phone);
            $userInfo = $this->getUserInfoByPhone($phoneEncrypt);
            if (empty($userInfo)) {
                continue;
            }
            $re = $rewardModel->createRewardRecord($userInfo['id'], RewardModel::REWARD_TYPE_4, 0, $currency, '巴比特活动奖励');
            echo sprintf("%s, 赠送50TB, %s", $phone, $re ? '成功' : '失败') . PHP_EOL;
        }
    }


    /**
     * 获取用户信息
     *
     * @param string $phone
     * @return array
     */
    public function getUserInfoByPhone(string $phone)
    {
        $phone = AesUtil::encrypt($phone);

        try {
            $row = PdoModel::getInstance(MysqlConfig::$baseConfig)->table('candy_user')->where('phone', '=', $phone)->getRow();
            return $row;
        } catch (\Exception $e) {
            return [];
        }
    }
}