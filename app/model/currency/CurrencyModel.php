<?php
/**
 * 货币
 *
 * User: forward
 * Date: 2018/7/19
 * Time: 下午8:03
 */

namespace App\model\currency;


use App\model\BaseModel;
use App\model\PdoModel;
use Config\db\MysqlConfig;
use Util\CacheUtil;

class CurrencyModel extends BaseModel
{
    const CURRENCY_STATUS_0 = 0;    //下线
    const CURRENCY_STATUS_1 = 1;    //上线

    private $table;

    public function __construct()
    {
        $this->table = 'candy_currency';
    }

    /**
     * 货币列表
     *
     * @return array
     */
    public function getCurrencyList()
    {
        $cacheKey = 'get:currency:list';
        if ($data = CacheUtil::getCache($cacheKey)) {
            return $data;
        }
        try {
            $data = PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)->where('currency_status', '=', self::CURRENCY_STATUS_1)->getList();
            if (empty($data)) {
                return [];
            }
            $data = array_combine(array_column($data, 'id'), $data);
            CacheUtil::setCache($cacheKey, $data, 1800);
            return $data;
        } catch (\Exception $e) {
            return [];
        }
    }
}