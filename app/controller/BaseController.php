<?php
/**
 * base controller
 *
 * User: forward
 * Date: 2018/6/21
 * Time: 下午11:34
 */

namespace App\controller;


use Slim\Http\Request;
use Slim\Http\Response;
use Util\LoggerUtil;

class BaseController
{
    protected $request;
    protected $response;
    protected $params;

    protected $postAction = [];     //POST提交方法
    protected $putAction = [];      //PUT提交方法

    protected $code;    //返回code
    protected $message; //返回message
    protected $data;    //返回data

    protected $path;

    protected $appendInfo = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->params = $request->getParams();

        $this->path = $request->getUri()->getPath();
        $this->checkMethod($this->path);
    }

    /**
     * 校验请求方式
     *
     * @param $path
     */
    private function checkMethod($path)
    {
        if (in_array($path, $this->postAction) && !$this->request->isPost()) {
            $this->renderJson(404, 'Method not found');
        }
        if (in_array($path, $this->putAction) && !$this->request->isPut()) {
            $this->renderJson(404, 'Method not found');
        }
    }

    /**
     * 获取字符串参数
     *
     * @param $key
     * @param string $default
     * @return string
     */
    public function get($key, string $default = '')
    {
        return $this->params[$key] ?? $default;
    }

    /**
     * 获取整形参数
     *
     * @param $key
     * @param int $default
     * @return int
     */
    public function getInt($key, int $default = 0)
    {
        return isset($this->params[$key]) ? intval($this->params[$key]) : $default;
    }

    /**
     * 获取浮点型参数
     *
     * @param $key
     * @param float $default
     * @return float
     */
    public function getFloat($key, float $default = 0.0)
    {
        return isset($this->params[$key]) ? floatval($this->params[$key]) : $default;
    }

    /**
     * 返回json
     *
     * @param int $code
     * @param string $message
     * @param array $data
     */
    public function renderJson($code = 0, $message = 'success', $data = [])
    {
        header('Content-Type:application/json;charset=UTF-8');
        $this->code = $code;
        $this->message = $message;
        $this->data = empty($data) ? new \stdClass() : $data;
        $ret = [
            'code' => $this->code,
            'message' => $this->message,
            'data' => $this->data,
            'timestamp' => $_SERVER['REQUEST_TIME']
        ];
        exit(json_encode($ret));
    }

    /**
     * 记录日志
     */
    public function __destruct()
    {
        $time = date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']);
        $params = json_encode($this->params, JSON_UNESCAPED_UNICODE);
        $return = empty($this->data) ? json_encode(new \stdClass()) : json_encode($this->data, JSON_UNESCAPED_UNICODE);

        $log = sprintf("%s|%s|%s|%s|%d|%s|%s|%s", $this->request->getMethod(), $this->path, $time, $params, $this->code, $this->message, $return, json_encode($this->appendInfo));

        LoggerUtil::getInstance()->accessLog($log);
    }
}