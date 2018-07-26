<?php
/**
 * 公司
 *
 * User: forward
 * Date: 2018/7/25
 * Time: 下午2:55
 */

namespace App\model\company;


use App\model\BaseModel;
use App\model\PdoModel;
use Config\db\MysqlConfig;
use Util\CacheUtil;

class CompanyModel extends BaseModel
{
    private $table;

    public function __construct()
    {
        $this->table = 'candy_company';
    }

    /**
     * 关于我们
     *
     * @param int $id
     * @return array
     */
    public function aboutUs($id = 1)
    {
        $cacheKey = 'about:us:' . $id;
        if ($data = CacheUtil::getCache($cacheKey)) {
            return $data;
        }
        try {
            $data = PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)->where('id', '=', $id)->getRow();
            CacheUtil::setCache($cacheKey, $data, 3600);
            return $data;
        } catch (\Exception $e) {
            return [];
        }
    }
}