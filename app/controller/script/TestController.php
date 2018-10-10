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
use function GuzzleHttp\Psr7\str;
use Util\AesUtil;

class TestController extends BaseController
{
    public function test()
    {
//        try {
//            $list = PdoModel::getInstance(MysqlConfig::$baseConfig)->table('candy_user')->getList(['id', 'nickname', 'phone']);
//            foreach ($list as &$v) {
//                $v['phone_1'] = empty($v['phone']) ? '' : AesUtil::decrypt($v['phone']);
//            }
//            var_dump($list);
//        } catch (\Exception $e) {
//            var_dump([]);
//        }
        $id = 0;
        while (true) {
            $list = $this->getUserListById($id);
            if (empty($list)) {
                exit();
            }
            $id = $list[count($list) - 1]['id'];
            $userIds = array_column($list, 'id');
            $assetList = $this->getAssetByUserIds($userIds);
            $phone = empty($v['phone']) ? '' : AesUtil::decrypt($v['phone']);
            $nickname = str_replace(',', '', $v['nickname']);
            foreach ($list as $v) {
                echo sprintf("%s,%s,%s,%s", $v['id'], $nickname, $phone, round($assetList[$v['id']], 2)) . PHP_EOL;
            }
            sleep(1);
        }
    }

    public function getUserListById(int $id = 0)
    {
        $userDb = PdoModel::getInstance(MysqlConfig::$baseConfig)->table('candy_user');
        try {
            return $userDb->where('id', '>', $id)->limit(200)->getList(['id', 'nickname', 'phone']);
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getAssetByUserIds(array $userIds)
    {
        $assetDb = PdoModel::getInstance(MysqlConfig::$baseConfig)->table('candy_asset');
        try {
            $list = $assetDb->whereIn('user_id', $userIds)->limit(200)->getList(['user_id', 'currency_number']);
            return array_column($list, 'currency_number', 'user_id');
        } catch (\Exception $e) {
            return [];
        }
    }
}