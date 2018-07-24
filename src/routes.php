<?php
/**
 * Created by PhpStorm.
 * User: forward
 * Date: 2018/7/17
 * Time: 上午11:40
 */

use Slim\Http\Request;
use Slim\Http\Response;

//App路由v1
$app->get('/v1/{controller}/{action}', function (Request $request, Response $response, $args) {
    $controller = !empty($args['controller']) ? ucfirst($args   ['controller']) . 'Controller' : 'IndexController';
    $action = $args['action'] ?? 'index';

    $controller = "\\App\\controller\\v1\\{$controller}";
    if (!class_exists($controller)) {
        (new \App\controller\BaseController($request, $response))->renderJson(404, 'Controller not found');
    }
    $o = new $controller($request, $response);
    if (!method_exists($o, $action)) {
        (new \App\controller\BaseController($request, $response))->renderJson(404, 'Method not found');
    }
    $params = $request->getParams();
    $o->$action($params);
});

//登录
$app->post('/v1/login', function (Request $request, Response $response, $args) {
    (new App\controller\v1\UserController($request, $response))->login();
});

//签到
$app->post('/v1/signIn', function (Request $request, Response $response, $args) {
    (new App\controller\v1\UserController($request, $response))->signIn();
});

//绑定手机号
$app->post('/v1/phone/bind', function (Request $request, Response $response, $args) {
    (new App\controller\v1\UserController($request, $response))->bindPhone();
});

//领取奖励
$app->post('/v1/reward', function (Request $request, Response $response, $args) {
    (new App\controller\v1\RewardController($request, $response))->reward();
});
