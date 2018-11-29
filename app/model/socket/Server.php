<?php
/**
 * Created by PhpStorm.
 * User: forward
 * Date: 2018/11/6
 * Time: 上午11:38
 */

namespace App\model\socket;

class Server
{
    /**
     * @throws \Exception
     */
    public function start()
    {
        //创建服务端的socket套接流,net协议为IPv4，protocol协议为TCP
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if (socket_bind($socket, '127.0.0.1', 8888) == false) {
            $this->error('server bind fail');
        }

        //监听套接流
        if (socket_listen($socket, 4) == false) {
            $this->error('server listen fail');
        }

        while (true) {
            //接收客户端传过来的信息
            $accept = socket_accept($socket);
            if (empty($accept)) {
                continue;
            }
            //读取客户端传过来的资源，并转化为字符串
            $string = socket_read($accept, 1024) . ' to';
            $return = 'server receive is :' . $string . PHP_EOL;
            echo $return;
            if (empty($string)) {
                echo 'socket_read is fail' . PHP_EOL;
                continue;
            }
            socket_write($accept, $return, strlen($return));
        }
        socket_close($socket);
    }

    /**
     * @throws \Exception
     */
    public function send()
    {
        //创建一个socket套接流
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if (!socket_connect($socket, '127.0.0.1', 8888)) {
            $this->error('connect fail massege');
        }

        $message = 'l love you 我爱你 socket';

        if (!socket_write($socket, $message, strlen($message))) {
            $this->error('fail to write');
        }
        echo 'client write success' . PHP_EOL;

        while ($callback = socket_read($socket, 1024)) {
            echo 'server return message is:' . PHP_EOL . $callback . PHP_EOL;
        }

        socket_close($socket);
    }

    /**
     * 异常
     *
     * @param string $msg
     * @throws \Exception
     */
    private function error(string $msg)
    {
        echo sprintf("%s: %s", $msg, socket_strerror(socket_last_error())) . PHP_EOL;
        throw new \Exception($msg);
    }
}