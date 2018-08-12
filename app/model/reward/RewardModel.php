<?php
/**
 * 奖励
 *
 * User: forward
 * Date: 2018/7/23
 * Time: 下午7:43
 */

namespace App\model\reward;


use App\model\asset\AssetModel;
use App\model\BaseModel;
use App\model\PdoModel;
use App\model\RedisModel;
use Config\db\MysqlConfig;
use Config\db\RedisConfig;
use Util\CacheUtil;
use Util\LoggerUtil;

class RewardModel extends BaseModel
{
    const REWARD_TYPE_1 = 1;    //任务奖励
    const REWARD_TYPE_2 = 2;    //挖矿奖励
    const REWARD_TYPE_3 = 3;    //邀请奖励

    const REWARD_LIST_PAGE_NUM = 30;    //奖励列表每页条数

    private $table;

    public function __construct()
    {
        $this->table = 'candy_reward';
    }

    /**
     * 创建奖励记录
     *
     * @param $userId
     * @param int $rewardType
     * @param int $foreignId
     * @param array $currency
     * @param string $description
     * @return bool
     */
    public function createRewardRecord($userId, int $rewardType, int $foreignId, array $currency, $description = '')
    {
        $description = addslashes($description);
        $sql1 = "INSERT INTO `{$this->table}` (user_id, reward_type, foreign_id, currency_id, currency_name, currency_number, reward_description) VALUES ({$userId}, {$rewardType}, {$foreignId}, {$currency['currency_id']}, '{$currency['currency_name']}', {$currency['currency_number']}, '{$description}')";
        $sql2 = "UPDATE `candy_asset` SET currency_number = currency_number + {$currency['currency_number']} WHERE user_id = {$userId} AND currency_id = {$currency['currency_id']} LIMIT 1";

        $sql = [
            $sql1,
            $sql2
        ];
        $assetCacheKey = sprintf("get:asset:by:user_id:%d:%d", $userId, 0);
        CacheUtil::delCache($assetCacheKey);
        $rewardCacheKey = sprintf("get:reward:list:%d:%d", $userId, 0);;          //清除奖励列表缓存
        CacheUtil::delCache($rewardCacheKey);
        try {
            return PdoModel::getInstance(MysqlConfig::$baseConfig)->executeTransaction($sql);
        } catch (\Exception $e) {
            LoggerUtil::getInstance()->info(sprintf("创建奖励记录异常，params=%s, exception=%s", func_get_args(), $e->getMessage()));
            return false;
        }
    }

    /**
     * 获取奖励排行
     *
     * @return array
     */
    public function getRewardRankList()
    {
        $key = sprintf('get:reward:list:%s', date('YmdH'));
        if ($list = CacheUtil::getCache($key)) {
            return $list;
        }
        $rankLastNumber = RedisRewardModel::getRankLastNumber();
        try {
            $list = PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)
                ->where('currency_id', '=', 1)
                ->where('currency_number', '>=', $rankLastNumber)
                ->order('currency_number DESC')
                ->limit(100)
                ->getList();
            if (empty($list)) {
                CacheUtil::setCache($key, $list, 1);
                return [];
            } else {
                CacheUtil::setCache($key, $list, 3600);
                $endRow = end($list);
                $rankLastNumber = $endRow['currency_number'];
                RedisRewardModel::setRankLastNumber($rankLastNumber);
                return $list;
            }
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * 获取奖励列表
     *
     * @param int $userId
     * @param int $id
     * @return array
     */
    public function getRewardList(int $userId, int $id)
    {
        if ($id < 0) {
            return [];
        }
        $cacheKey = sprintf("get:reward:list:%d:%d", $userId, $id);
        if ($id === 0 && $data = CacheUtil::getCache($cacheKey)) {  //只缓存第一页
            return $data;
        }
        try {
            $pdo = PdoModel::getInstance(MysqlConfig::$baseConfig)->table($this->table)
                ->where('user_id', '=', $userId)
                ->order('id desc')
                ->limit(self::REWARD_LIST_PAGE_NUM);
            if ($id !== 0) {
                $pdo->where('id', '<', $id);
            }
            $list = $pdo->getList(['id', 'currency_name', 'currency_number', 'reward_description', 'created_at']);
            if (empty($list)) {
                return [];
            }
            foreach ($list as &$v) {
                $v['currency_name'] = AssetModel::TB_NAME;
                $v['currency_number'] = round($v['currency_number'], 6);
                $v['created_at'] = self::getTimeStr($v['created_at']);
            }
            if ($id === 0) {
                CacheUtil::setCache($cacheKey, $list, 600);
            }
            return $list;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * 格式化领取时间
     *
     * @param string $createdAt
     * @return string
     */
    private static function getTimeStr($createdAt = '')
    {
        $rewardTime = strtotime($createdAt);
        $time = $_SERVER['REQUEST_TIME'] - $rewardTime;
        if ($time < 60) {       //1分钟内
            return '刚刚';
        }
        if ($time < 3600) {     //1小时内
            return sprintf('%d分钟前', intval($time / 60));
        }
        if ($time < 86400) {    //1天内
            return sprintf('%d小时前', intval($time / 3600));
        }
        if ($time < 86400 * 365) {  //1年内
            return sprintf('%d天前', intval($time / 86400));
        }
        return '1年前';
    }
}