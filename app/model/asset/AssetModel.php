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
use App\model\exchange\ExchangeModel;
use App\model\PdoModel;
use Config\db\MysqlConfig;
use Util\CacheUtil;
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
     * @return bool
     */
    public function createUserAsset(int $userId)
    {
        $currencyList = (new CurrencyModel())->getCurrencyList();
        if (empty($currencyList)) {
            return false;
        }
        $sql = [];
        foreach ($currencyList as $currency) {
            $sql[] = "INSERT INTO `{$this->table}` (user_id, currency_id, currency_name) VALUES ({$userId}, {$currency['id']}, '{$currency['currency_name']}')";
        }
        try {
            return PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)->executeTransaction($sql);
        } catch (\Exception $e) {
            LoggerUtil::getInstance()->warning(sprintf("生成用户资金账户异常，user_id = %d, exception = %s", $userId, $e->getMessage()));
            return false;
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
     * 获取用户总资产
     *
     * @param int $userId
     * @param string $currencyName
     * @return array
     */
    public function getUserTotalAsset(int $userId, string $currencyName = 'TB')
    {
        return $this->calculateUserTotalAsset($userId, $currencyName, $this->getAssetByUserId($userId));
    }

    /**
     * 计算用户总资产
     *
     * @param int $userId
     * @param string $currencyName
     * @param array $assetList
     * @return array
     */
    public function calculateUserTotalAsset(int $userId, string $currencyName = 'TB', array $assetList = [])
    {
        if (empty($userId) || empty($currencyName) || empty($assetList)) {
            return [
                'currency_name' => 'TB',
                'currency_number' => 0
            ];
        }
//        $cacheKey = sprintf("get:user:total:asset:%d:%s", $userId, $currencyName);
//        if ($data = CacheUtil::getCache($cacheKey)) {
//            return $data;
//        }
        $pairList = (new ExchangeModel())->getPairList();
        $asset = 0;
        foreach ($assetList as $v) {
            $pair = strtolower(sprintf("%s-%s", $v['currency_name'], $currencyName));
            $exchangeRate = $pairList[$pair]['exchange_rate'] ?? 0;
            $asset += $v['currency_number'] * $exchangeRate;
        }
        $data = [
            'currency_name' => $currencyName,
            'currency_number' => $asset
        ];
//        CacheUtil::setCache($cacheKey, $data, 5);
        return $data;
    }

    /**
     * 获取用户资产列表 不包含总资产
     *
     * @param int $userId
     * @param int $currencyId 为0表示获取所有资产列表
     * @return array
     */
    public function getAssetByUserId(int $userId, $currencyId = 0)
    {
        $cacheKey = sprintf("get:asset:by:user_id:%d:%d", $userId, $currencyId);
        if ($data = CacheUtil::getCache($cacheKey)) {
            return $data;
        }
        $currencyList = (new CurrencyModel())->getCurrencyList();   //货币配置

        $fields = ['id', 'currency_id', 'currency_number'];
        try {
            $pdo = PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)->where('user_id', '=', $userId);
            if (!empty($currencyId)) {
                $pdo->where('currency_id', '=', $currencyId);
            }
            $assetList = $pdo->getList($fields);
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
            $v['currency_number'] = floatval($v['currency_number']);
        }

        CacheUtil::setCache($cacheKey, $data, 3);
        return $assetList;
    }
}