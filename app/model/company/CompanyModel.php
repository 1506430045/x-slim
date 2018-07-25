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
     * @return array
     */
    public function aboutUs()
    {
        try {
            return PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)->getRow();
        } catch (\Exception $e) {
            return [];
        }
    }
}