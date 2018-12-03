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
