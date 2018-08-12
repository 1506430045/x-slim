<?php
/**
 * 每小时生成排行数据 星钻排行 && 邀请排行
 * User: forward
 * Date: 2018/8/12
 * Time: 下午2:29
 */

namespace App\controller\script;


use App\model\PdoModel;
use App\model\RedisModel;
use Config\db\MysqlConfig;
use Config\db\RedisConfig;

class RankController
{
    const CURRENCY_ID = 1;                          //星钻货币ID
    const RANK_NUMBER = 100;                        //排名数量
    const RANK_LAST_NUMBER = 'rank:%d:last:number'; //排名最后一名的数量

    public function run()
    {

    }

    private function getRewardRank()
    {

    }

    private function getInviteRank()
    {

    }
}