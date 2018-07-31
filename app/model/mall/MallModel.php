<?php
/**
 * 商城
 *
 * User: forward
 * Date: 2018/7/25
 * Time: 下午2:24
 */

namespace App\model\mall;


use App\model\asset\AssetModel;
use App\model\BaseModel;
use App\model\PdoModel;
use Config\db\MysqlConfig;
use Util\CacheUtil;

class MallModel extends BaseModel
{
    const GOODS_STATUS_0 = 0;   //未上架
    const GOODS_STATUS_1 = 1;   //已上架

    private $table;

    public function __construct()
    {
        $this->table = 'candy_goods';
    }

    /**
     * 获取商品列表
     *
     * @return array
     */
    public function getGoodsList()
    {
        $cacheKey = 'get:goods:list';
        if ($data = CacheUtil::getCache($cacheKey)) {
            return $data;
        }
        $fields = [
            'id', 'goods_name', 'goods_img', 'stock', 'currency_name', 'currency_number'
        ];
        try {
            $list = PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)->where('goods_status', '=', self::GOODS_STATUS_1)->getList($fields);
            if (empty($list)) {
                return [];
            }
            foreach ($list as &$v) {
                $v['currency_number'] = round($v['currency_number'], 6);
                $v['currency_name'] = AssetModel::TB_NAME;
            }
            CacheUtil::setCache($cacheKey, $list);
            return $list;
        } catch (\Exception $e) {
            return [];
        }
    }
}