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
//404
$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(404)
            ->withHeader('Content-Type', 'Content-Type:application/json;charset=UTF-8')
            ->write(json_encode([
                'code' => 404,
                'message' => 'File not found',
                'data' => [],
                'timestamp' => $_SERVER['REQUEST_TIME']
            ]));
    };
};
//5XX
$c['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return $c['response']->withStatus(500)
            ->withHeader('Content-Type', 'Content-Type:application/json;charset=UTF-8')
            ->write(json_encode([
                'code' => 500,
                'message' => 'Something went wrong!',
                'data' => [],
                'timestamp' => $_SERVER['REQUEST_TIME']
            ]));
    };
};

$app = new Slim\App($c);
// Register routes
require __DIR__ . '/../src/routes.php';

$app->run();