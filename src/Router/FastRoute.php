<?php
/**
 * User: isliang
 * Date: 2019/9/12
 * Time: 14:08
 * Email: wslhdu@163.com
 **/

namespace Isf\Router;

use FastRoute\Dispatcher;
use Isf\Exception\ClassNotFoundException;
use Isf\Exception\IsfException;
use Isf\Exception\MethodNotExistException;
use Isf\Exception\RequestMethodNotAllowedException;
use Isf\Exception\RouteNotFoundException;
use Swoole\Http\Request;
use Swoole\Http\Response;

class FastRoute extends BaseRoute
{
    /**
     * @param $request Request
     * @param $response Response
     * @throws IsfException
     */
    public static function dispatch($request, $response)
    {
        self::init();
        $request_method = $request->server['request_method'];
        $uri = $request->server['request_uri'];
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
                call_user_func_array([new $class($request, $response), $handler], []);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new RequestMethodNotAllowedException($request_method, $uri);
                break;
        }
    }
}