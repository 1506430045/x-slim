<?php
/**
 * 资产
 *
 * User: forward
 * Date: 2018/7/19
 * Time: 下午8:09
 */

namespace App\model\asset;


use App\model\BaseModel;
use App\model\currency\CurrencyModel;
use App\model\PdoModel;
use Config\db\MysqlConfig;
use Util\LoggerUtil;

class AssetModel extends BaseModel
{
    private $table;

    public function __construct()
    {
        $this->table = 'candy_asset';
    }

    /**
     * 创建用户资产数据
     *
     * @param int $userId
     * @param int $currencyId
     * @return int
     */
    public function createUserAsset(int $userId, int $currencyId = 1)
    {
        $data = [
            'user_id' => $userId,
            'currency_id' => $currencyId
        ];
        try {
            return PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)->insert($data);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * 资产增加减少操作
     *
     * @param int $userId
     * @param int $currencyId
     * @param float $currencyNumber
     * @return int
     */
    public function opAsset(int $userId, int $currencyId, float $currencyNumber = 0.0)
    {
        $sql = "UPDATE {$this->table} SET currency_number = currency_number + {$currencyNumber} WHERE user_id = {$userId} AND currency_id = {$currencyId} LIMIT 1";
        try {
            return PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)->execute($sql);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * 获取用户资产列表
     *
     * @param int $userId
     * @return array
     */
    public function getAssetByUserId(int $userId)
    {
        $currencyList = (new CurrencyModel())->getCurrencyList();   //货币配置

        $fields = ['id', 'currency_id', 'currency_number'];
        try {
            $assetList = PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)->where('user_id', '=', $userId)->getList($fields);
        } catch (\Exception $e) {
            return [];
        }

        if (empty($assetList)) {
            return [];
        }

        foreach ($assetList as &$v) {
            $currencyInfo = $currencyList[$v['currency_id']];
            $v['icon'] = $currencyInfo['currency_icon'];
            $v['currency_name'] = $currencyInfo['currency_name'];
            $v['description'] = $currencyInfo['currency_description'];
        }

        return $assetList;
    }
}