<?php
/**
 * User: isliang
 * Date: 2019/9/26
 * Time: 10:14
 * Email: wslhdu@163.com.
 **/

namespace Ipf\Router;

use FastRoute\Dispatcher;
use Ipf\Exception\ClassNotFoundException;
use Ipf\Exception\MethodNotExistException;
use Ipf\Exception\RequestMethodNotAllowedException;
use Ipf\Exception\RouteNotFoundException;

class FpmRoute extends BaseRoute
{
    public static function dispatch($request, $response)
    {
        self::init();
        $request_method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        $routeInfo = self::$dispatcher->dispatch($request_method, $uri);
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new RouteNotFoundException($uri);
                break;
            case Dispatcher::FOUND:
                list($class, $handler) = explode('#', $routeInfo[1]);
                if (!class_exists($class)) {
                    throw new ClassNotFoundException($class);
                }
                if (!method_exists($class, $handler)) {
                    throw new MethodNotExistException($class, $handler);
                }
                call_user_func_array([new $class(), $handler], []);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new RequestMethodNotAllowedException($request_method, $uri);
                break;
        }
    }
}
