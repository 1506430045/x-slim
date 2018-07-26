<?php
/**
 * 汇率
 *
 * User: forward
 * Date: 2018/7/26
 * Time: 下午3:34
 */

namespace App\model\exchange;


use App\model\BaseModel;
use App\model\PdoModel;
use Config\db\MysqlConfig;
use Util\CacheUtil;

class ExchangeModel extends BaseModel
{
    private $table = 'table';

    public function __construct()
    {
        $this->table = 'candy_exchange';
    }

    /**
     * 获取所有汇率
     *
     * @return array
     */
    public function getPairList()
    {
        $cacheKey = 'get:pair:list';
        if ($data = CacheUtil::getCache($cacheKey)) {
            return $data;
        }
        try {
            $fields = ['pair', 'currency_id', 'to_currency_id', 'exchange_rate'];
            $list = PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)->getList($fields);
            if (empty($list)) {
                return [];
            }
            $result = [];
            foreach ($list as &$v) {
                $pair = $v['pair'];
                $result[$pair] = [
                    'pair' => $v['pair'],
                    'currency_id' => $v['currency_id'],
                    'to_currency_id' => $v['to_currency_id'],
                    'exchange_rate' => $v['exchange_rate']
                ];
            }
            CacheUtil::setCache($cacheKey, $result, 600);
            return $result;
        } catch (\Exception $e) {
            return [];
        }
    }
}