<?php
/**
 * Pdo实例
 *
 * User: forward
 * Date: 2018/6/25
 * Time: 下午6:29
 */

namespace App\model;


use Util\LoggerUtil;

class PdoModel
{
    private $dbName;
    private $dbConfig;
    private $table;
    public $dbh;

    private $skip = 0;
    private $limit = 20;
    private $order = 'id ASC';
    private $where = [];
    private static $_instances = [];

    private function __construct($dbConfig = [])
    {
        $this->dbName = $dbConfig['dbname'];
        $this->dbConfig = $dbConfig;
        $dsn = "mysql:host={$this->dbConfig['host']};dbname={$this->dbName};port=3306;charset=UTF8";
        $this->dbh = new \PDO($dsn, $this->dbConfig['username'], $this->dbConfig['password'], $this->dbConfig['options']);
    }

    /**
     * 获取实例
     *
     * @param array $dbConfig
     * @return PdoModel
     */
    public static function getInstance($dbConfig = [])
    {
        ksort($dbConfig);
        $key = md5(serialize($dbConfig));
//        if (isset(self::$_instances[$key]) && self::$_instances[$key] instanceof self && self::$_instances[$key]->ping()) {
//            return self::$_instances[$key];
//        }
        self::$_instances[$key] = new self($dbConfig);
        return self::$_instances[$key];
    }

    /**
     * 设置表
     *
     * @param string $tableName
     * @return $this
     */
    public function table($tableName = '')
    {
        $this->table = $tableName;
        return $this;
    }

    /**
     * mysql连接是否可用
     *
     * @return bool
     */
    public function ping()
    {
        try {
            $this->dbh->getAttribute(\PDO::ATTR_SERVER_INFO);
        } catch (\PDOException $e) {
            if (strpos($e->getMessage(), 'MySQL server has gone away') !== false) {
                return false;
            }
        }
        return true;
    }

    /**
     * where
     *
     * @param $field
     * @param $op
     * @param $value
     * @return $this
     */
    public function where($field, $op, $value)
    {
        $this->where[] = [
            'key' => $field,
            'operate' => $op,
            'value' => $value
        ];
        return $this;
    }

    /**
     * where in
     *
     * @param $field
     * @param array $arr
     * @return $this
     */
    public function whereIn($field, array $arr = [])
    {
        if (empty($arr)) {
            return $this;
        }
        return $this->where($field, 'IN', $arr);
    }

    /**
     * 查询单条数据
     *
     * @param array $fields
     * @return array
     * @throws \Exception
     */
    public function getRow($fields = [])
    {
        $fields = self::_parseFields($fields);
        try {
            $parseWhere = $this->_parseWhere($this->where);
            $sql = "SELECT {$fields} FROM `{$this->table}` WHERE {$parseWhere['whereStr']} LIMIT 1";
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute($parseWhere['bindArr']);
            $this->resetCondition();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $this->resetCondition();
            return [];
        }
    }

    /**
     * 设置skip
     *
     * @param int $skip
     * @return $this
     */
    public function skip(int $skip = 0)
    {
        if ($skip <= 0) {
            return $this;
        }
        $this->skip = $skip;
        return $this;
    }

    /**
     * 设置limit
     *
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit = 0)
    {
        if ($limit <= 0) {
            return $this;
        }
        $this->limit = $limit;
        return $this;
    }

    /**
     * 设置order
     *
     * @param string $order
     * @return $this
     */
    public function order($order = 'id ASC')
    {
        if (empty($order)) {
            return $this;
        }
        $this->order = $order;
        return $this;
    }

    /**
     * 查询列表数据数据 常规查询
     *
     * @param array $fields
     * @return array
     * @throws \Exception
     */
    public function getList($fields = [])
    {
        $fields = self::_parseFields($fields);
        try {
            $parseWhere = $this->_parseWhere($this->where);
            $sql = "SELECT {$fields} FROM `{$this->table}` WHERE {$parseWhere['whereStr']} ORDER BY {$this->order} LIMIT {$this->skip}, {$this->limit}";
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute($parseWhere['bindArr']);
            $this->resetCondition();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            $this->resetCondition();
            return [];
        }
    }

    /**
     * 初始化条件
     */
    private function resetCondition()
    {
        $this->skip = 0;
        $this->limit = 20;
        $this->order = 'id ASC';
        $this->where = [];
    }

    /**
     * 查询 自己写复杂sql
     *
     * @param $sql
     * @param bool $one
     * @return array|mixed
     */
    public function query($sql, $one = false)
    {
        $sql = addslashes($sql);
        try {
            $stmt = $this->dbh->query($sql);
            return $one ? $stmt->fetch(\PDO::FETCH_ASSOC) : $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * 删除数据
     *
     * @return int
     * @throws \Exception
     */
    public function delete()
    {
        try {
            $parseWhere = $this->_parseWhere($this->where);
            $sql = "DELETE FROM `{$this->table}` WHERE {$parseWhere['whereStr']}";
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute($parseWhere['bindArr']);
            $this->resetCondition();
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            $this->resetCondition();
            return 0;
        }
    }

    /**
     * 更新数据
     *
     * @param array $update
     * @return int
     * @throws \Exception
     */
    public function update(array $update)
    {
        try {
            $parseUpdate = self::_parseUpdate($update);
            $parseWhere = $this->_parseWhere($this->where);
            $sql = "UPDATE `{$this->table}` SET {$parseUpdate['whereStr']} WHERE {$parseWhere['whereStr']}";
            $stmt = $this->dbh->prepare($sql);
            $bindArr = array_merge($parseUpdate['bindArr'], $parseWhere['bindArr']);
            $stmt->execute($bindArr);
            $this->resetCondition();
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            LoggerUtil::getInstance()->notice($e->getMessage(), ['method' => __METHOD__, 'params' => $update]);
            $this->resetCondition();
            if ($e->getCode() === '23000') {
                return -1;
            }
            return 0;
        }
    }

    /**
     * 插入语句 0插入失败 -1唯一约束重复 正数插入成功
     *
     * @param array $arr
     * @return int
     * @throws \Exception
     */
    public function insert(array $arr)
    {
        $parseInsert = self::_parseInsert($arr);
        try {
            $sql = "INSERT INTO `{$this->table}` ({$parseInsert['keyStr']}) VALUES ({$parseInsert['bindKeyStr']})";
            $stmt = $this->dbh->prepare($sql);
            $re = $stmt->execute($parseInsert['bindArr']);
            $this->resetCondition();
            return $re ? intval($this->dbh->lastInsertId()) : 0;
        } catch (\PDOException $e) {
//            LoggerUtil::getInstance()->notice($e->getMessage(), ['method' => __METHOD__, 'params' => $arr]);
            $this->resetCondition();
            if ($e->getCode() === '23000') {
                return -1;
            }
            return 0;
        }
    }

    /**
     * 查询 自己写复杂sql
     *
     * @param string $sql
     * @return int
     */
    public function execute($sql = '')
    {
        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            return 0;
        }
    }

    /**
     * 事务操作
     *
     * @param array $tranStr
     * @return bool
     */
    public function executeTransaction(array $tranStr)
    {
        try {
            $this->dbh->beginTransaction();
            foreach ($tranStr as $stateStr) {
                $this->dbh->exec($stateStr);
            }
            return $this->dbh->commit();
        } catch (\PDOException $e) {
            $this->dbh->rollBack();
            return false;
        }
    }

    /**
     * 解析where
     *
     * @param array $where
     * @return array
     * @throws \Exception
     */
    private function _parseWhere(array $where)
    {
        if (empty($where)) {
            return [
                'whereStr' => '1 = 1',
                'bindArr' => []
            ];
        }
        $whereStr = '';
        $bindArr = [];
        foreach ($where as $item) {
            if ($item['operate'] === 'in' || $item['operate'] === 'IN') {
                $bindKeyStr = '';
                $bindIndex = 0;
                foreach ($item['value'] as $itemVal) {
                    $bindKey = ':bindKey' . $bindIndex++;
                    $bindKeyStr .= $bindKey . ', ';
                    $bindArr[$bindKey] = $itemVal;
                }
                $bindKeyStr = rtrim($bindKeyStr, ', ');
                $whereStr .= "`{$item['key']}` {$item['operate']} ($bindKeyStr) AND ";
            } else {
                $bindKey = ':w_' . $item['key'];
                $whereStr .= "`{$item['key']}` {$item['operate']} $bindKey AND ";
                $bindArr[$bindKey] = $item['value'];
            }
        }
        $this->where = [];
        return [
            'whereStr' => rtrim($whereStr, 'AND '),
            'bindArr' => $bindArr
        ];
    }

    /**
     * 解析update
     *
     * @param array $update
     * @return array
     * @throws \Exception
     */
    private static function _parseUpdate(array $update)
    {
        if (!is_array($update) || empty($update)) {
            throw new \Exception('分析update语句失败, update参数不能为空');
        }
        $whereStr = '';
        $bindArr = [];
        foreach ($update as $k => $value) {
            $bindKey = ':k_' . $k;
            $whereStr .= "`{$k}` = $bindKey AND ";
            $bindArr[$bindKey] = $value;
        }
        return [
            'whereStr' => rtrim($whereStr, 'AND '),
            'bindArr' => $bindArr
        ];
    }

    /**
     * 解析插入语句
     *
     * @param array $arr
     * @return array
     * @throws \Exception
     */
    private static function _parseInsert(array $arr)
    {
        if (!is_array($arr) || empty($arr)) {
            throw new \Exception('分析insert语句失败, arr参数不能为空');
        }
        $separator = ', ';
        $keyStr = '';
        $bindKeyStr = '';
        $bindArr = [];
        foreach ($arr as $k => $value) {
            $bindKey = ':k_' . $k;
            $keyStr .= "`{$k}` {$separator}";
            $bindKeyStr .= $bindKey . $separator;
            $bindArr[$bindKey] = $value;
        }
        return [
            'keyStr' => rtrim($keyStr, $separator),
            'bindKeyStr' => rtrim($bindKeyStr, $separator),
            'bindArr' => $bindArr
        ];
    }

    /**
     * 解析fields字段
     *
     * @param array $fields
     * @return string
     * @throws \Exception
     */
    private static function _parseFields(array $fields)
    {
        $fieldStr = '';
        if (empty($fields) || !is_array($fields)) {
            return '*';
        }
        foreach ($fields as $field) {
            if (!is_string($field)) {
                throw new \Exception("field必须是字符串,field=" . json_encode($field), '-1');
            }
            $fieldStr .= "`{$field}`, ";
        }
        return rtrim($fieldStr, ', ');
    }

    /**
     * 禁止克隆
     */
    public function __clone()
    {
        trigger_error('pdo instance can not clone', E_USER_ERROR);
    }
}