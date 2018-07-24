<?php
/**
 * Logger
 *
 * User: forward
 * Date: 2018/7/4
 * Time: 下午5:07
 */

namespace Util;


class LoggerUtil
{
    const EMERGENCY = 'emergency';
    const ALERT = 'alert';
    const CRITICAL = 'critical';
    const ERROR = 'error';
    const WARNING = 'warning';
    const NOTICE = 'notice';
    const INFO = 'info';
    const DEBUG = 'debug';

    private $logPath = '';
    private $logDate = '';
    private $logTime = '';

    private static $_instance;

    private function __construct($logPath = '/var/log/candy-api')
    {
        $this->logPath = $logPath;
        $this->logDate = date('Y-m-d', $_SERVER['REQUEST_TIME']);
        $this->logTime = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
    }

    /**
     * 获取实例
     *
     * @param string $logPath
     * @return LoggerUtil
     */
    public static function getInstance($logPath = '/var/log/candy-api')
    {
        if (!empty(self::$_instance) && self::$_instance instanceof self) {
            return self::$_instance;
        }
        return new self($logPath);
    }

    public function setLogger($logger)
    {
    }

    /**
     * 系统不可用
     *
     * @param $message
     * @param array $context
     * @return bool
     */
    public function emergency($message, array $context = array())
    {
        return $this->log(self::EMERGENCY, $message, $context);
    }

    /**
     * **必须**立刻采取行动
     *
     * 例如：在整个网站都垮掉了、数据库不可用了或者其他的情况下，**应该**发送一条警报短信把你叫醒。
     *
     * @param $message
     * @param array $context
     * @return bool
     */
    public function alert($message, array $context = array())
    {
        return $this->log(self::ALERT, $message, $context);
    }

    /**
     * 紧急情况
     *
     * 例如：程序组件不可用或者出现非预期的异常。
     *
     * @param $message
     * @param array $context
     * @return bool
     */
    public function critical($message, array $context = array())
    {
        return $this->log(self::CRITICAL, $message, $context);
    }

    /**
     * 运行时出现的错误，不需要立刻采取行动，但必须记录下来以备检测。
     *
     * @param $message
     * @param array $context
     * @return bool
     */
    public function error($message, array $context = array())
    {
        return $this->log(self::ERROR, $message, $context);
    }

    /**
     * 出现非错误性的异常。
     *
     * 例如：使用了被弃用的API、错误地使用了API或者非预想的不必要错误。
     *
     * @param $message
     * @param array $context
     * @return bool
     */
    public function warning($message, array $context = array())
    {
        return $this->log(self::WARNING, $message, $context);
    }

    /**
     * 一般性重要的事件
     *
     * @param $message
     * @param array $context
     * @return bool
     */
    public function notice($message, array $context = array())
    {
        return $this->log(self::NOTICE, $message, $context);
    }

    /**
     * * 重要事件
     *
     * 例如：用户登录和SQL记录。
     *
     * @param $message
     * @param array $context
     * @return bool
     */
    public function info($message, array $context = array())
    {
        return $this->log(self::INFO, $message, $context);
    }

    /**
     * debug 详情
     *
     * @param $message
     * @param array $context
     * @return bool
     */
    public function debug($message, array $context = array())
    {
        return $this->log(self::DEBUG, $message, $context);
    }

    /**
     * 接口访问日志
     *
     * @param $log
     * @return bool
     */
    public function accessLog($log)
    {
        file_put_contents("{$this->logPath}/candy-api-access-{$this->logDate}.log", $log . PHP_EOL, FILE_APPEND);
        return true;
    }

    /**
     * guzzle异常日志
     *
     * @param $log
     * @return bool
     */
    public function guzzleLog($log)
    {
        file_put_contents("{$this->logPath}/guzzle-exception-{$this->logDate}.log", $log . PHP_EOL, FILE_APPEND);
        return true;
    }

    /**
     * 任意等级的日志记录
     *
     * @param $level
     * @param $message
     * @param array $context
     * @return bool
     */
    private function log($level, $message, array $context = [])
    {
        $filename = sprintf("%s/%s-%s.log", $this->logPath, $level, $this->logDate);
        $context = empty($context) ? new \stdClass() : $context;
        $log = sprintf("%s|%s|%s|%s", $this->logTime, $level, $message, json_encode($context, JSON_UNESCAPED_UNICODE));
        file_put_contents($filename, $log . PHP_EOL, FILE_APPEND);
        return true;
    }
}