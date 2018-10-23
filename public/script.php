<?php
/**
 * 脚本入口
 *
 * User: forward
 * Date: 2018/7/18
 * Time: 下午3:06
 */
declare(strict_types=1);    //开启严格模式
require __DIR__ . '/../vendor/autoload.php';

$param = getopt("c:a:");

if (empty($param['c']) || empty($param['a'])) {
    exit(json_encode([
        'code' => 400,
        'message' => '参数有误,请检查',
        'data' => []
    ]));
}

$controller = ucfirst($param['c']) . 'Controller';
$action = $param['a'];

$controller = "\\App\\controller\\script\\{$controller}";
if (!class_exists($controller)) {
    exit(json_encode([
        'code' => 400,
        'message' => '控制器不存在,请检查',
        'data' => []
    ]));
}
$o = new $controller();
if (!method_exists($o, $action)) {
    exit(json_encode([
        'code' => 400,
        'message' => '方法不存在,请检查',
        'data' => []
    ]));
}
$o->$action();

