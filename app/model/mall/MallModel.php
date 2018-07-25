<?php
/**
 * 商城
 *
 * User: forward
 * Date: 2018/7/25
 * Time: 下午2:24
 */

namespace App\model\mall;


use App\model\BaseModel;
use App\model\PdoModel;
use Config\db\MysqlConfig;

class MallModel extends BaseModel
{
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
        $fields = [
            'id', 'goods_name', 'goods_img', 'stock', 'currency_name', 'currency_number'
        ];
        try {
            $list = PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)->getList($fields);
            if (empty($list)) {
                return [];
            }
            foreach ($list as &$v) {
                $v['currency_number'] = floatval($v['currency_number']);
            }
            return $list;
        } catch (\Exception $e) {
            return [];
        }
    }
}