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
use Util\AesUtil;

class TestController extends BaseController
{
    public function test()
    {
        try {
            $list = PdoModel::getInstance(MysqlConfig::$baseConfig)->table('candy_user')->getList(['id', 'nickname', 'phone']);
            foreach ($list as &$v) {
                $v['phone_1'] = empty($v['phone']) ? '' : AesUtil::decrypt($v['phone']);
            }
            var_dump($list);
        } catch (\Exception $e) {
            var_dump([]);
        }
    }
}