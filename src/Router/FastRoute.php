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
use Ipf\Http\Request\RequestFactory;
use Ipf\Http\Response\ResponseFactory;
use Swoole\Http\Request;
use Swoole\Http\Response;

class FastRoute extends BaseRoute
{
    /**
     * @param Request $req
     * @param Response $res
     * @throws ClassNotFoundException
     * @throws MethodNotExistException
     * @throws RequestMethodNotAllowedException
     * @throws RouteNotFoundException
     */
    public static function dispatch($req = null, $res = null)
    {
        $request = RequestFactory::getRequest($req);
        $response = ResponseFactory::getResponse($res);
        self::init();
        $request_method = $request->getMethod();
        $uri = $request->getUri()->getPath();
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
