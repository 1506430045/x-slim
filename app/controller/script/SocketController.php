<?php
/**
 * Created by PhpStorm.
 * User: forward
 * Date: 2018/11/6
 * Time: 上午11:53
 */

namespace App\controller\script;


use App\model\socket\Server;

class SocketController extends BaseController
{
    public function server()
    {
        try {
            (new Server())->start();
        } catch (\Exception $e) {
        }
    }

    public function send()
    {
        try {
            (new Server())->send();
        } catch (\Exception $e) {

        }
    }
}