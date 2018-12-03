<?php
declare(strict_types=1);    //开启严格模式
/**
 * api入口
 *
 * User: forward
 * Date: 2018/6/21
 * Time: 下午9:47
 */

require __DIR__ . '/../vendor/autoload.php';

//session_start();

$c = new \Slim\Container();

$app = new Slim\App($c);
// Register routes
require __DIR__ . '/../src/routes.php';

try {
    $app->run(false);
} catch (Exception $e) {
}