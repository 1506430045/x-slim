<?php
/**
 * Created by PhpStorm.
 * User: forward
 * Date: 2018/7/31
 * Time: 下午1:42
 */

namespace App\controller\script;


use App\model\PdoModel;
use Config\db\MysqlConfig;

class TestController extends BaseController
{
    public function test()
    {
        $data = [
            'openid' => 'xiangqiantest',
            'nickname' => '向前😝'
        ];
        $re = PdoModel::getInstance(MysqlConfig::$baseConfig)->table('candy_user')->insert($data);
        var_dump($re);
    }
}