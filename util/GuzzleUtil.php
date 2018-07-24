<?php
/**
 * Guzzle请求
 *
 * User: forward
 * Date: 2018/6/25
 * Time: 下午9:15
 */

namespace Util;


use GuzzleHttp\Client;

class GuzzleUtil
{
    private static $_clients = [];
    private $client;

    private function __construct($baseUri, $timeout = 5)
    {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'timeout' => $timeout
        ]);
    }

    /**
     * 获取客户端
     *
     * @param $baseUri
     * @param $timeout
     * @return GuzzleUtil
     */
    public static function getClient($baseUri, $timeout = 5)
    {
        $key = md5($baseUri . $timeout);
        if (isset(self::$_clients[$key]) && self::$_clients[$key] instanceof self) {
            return self::$_clients[$key];
        }
        self::$_clients[$key] = new self($baseUri, $timeout);
        return self::$_clients[$key];
    }

    /**
     * get请求
     *
     * @param $uri
     * @param array $params
     * @param array $headers
     * @param array $auth
     * @return array
     */
    public function get($uri, $params = [], $headers = [], $auth = [])
    {
        $options = [
            'query' => $params,
            'headers' => $headers,
            'auth' => $auth
        ];
        try {
            $content = $this->client->get($uri, $options)->getBody()->getContents();
            return [
                'status' => true,
                'data' => json_decode($content, true) ?: $content
            ];
        } catch (\Exception $e) {
            $this->_log('GET', $uri, $params, $e);
            return [
                'status' => false,
                'data' => []
            ];
        }
    }

    /**
     * post请求
     *
     * @param $uri
     * @param array $params
     * @param array $headers
     * @param array $auth
     * @return array
     */
    public function post($uri, $params = [], $headers = [], $auth = [])
    {
        $options = [
            'form_params' => $params,
            'headers' => $headers,
            'auth' => $auth
        ];
        try {
            $content = $this->client->post($uri, $options)->getBody()->getContents();
            return [
                'status' => true,
                'data' => json_decode($content, true) ?: $content
            ];
        } catch (\Exception $e) {
            $this->_log('POST', $uri, $params, $e);
            return [
                'status' => false,
                'data' => []
            ];
        }
    }

    /**
     * delete请求
     *
     * @param $uri
     * @param array $params
     * @param array $headers
     * @param array $auth
     * @return array
     */
    public function delete($uri, $params = [], $headers = [], $auth = [])
    {
        $options = [
            'form_params' => $params,
            'headers' => $headers,
            'auth' => $auth
        ];
        try {
            $content = $this->client->delete($uri, $options)->getBody()->getContents();
            return [
                'status' => true,
                'data' => json_decode($content, true) ?: $content
            ];
        } catch (\Exception $e) {
            $this->_log('DELETE', $uri, $params, $e);
            return [
                'status' => false,
                'data' => []
            ];
        }
    }

    /**
     * 记录日志
     *
     * @param string $method
     * @param $uri
     * @param $params
     * @param \Exception $e
     */
    private function _log($method = 'GET', $uri, $params, \Exception $e)
    {
        $log = sprintf('%s|%s|%s|%s', $method, $uri, json_encode($params, JSON_UNESCAPED_UNICODE), $e->getMessage());

        LoggerUtil::getInstance()->guzzleLog($log);
    }
}