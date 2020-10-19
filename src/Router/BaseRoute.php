<?php
/**
 * User: isliang
 * Date: 2019/9/26
 * Time: 10:16
 * Email: wslhdu@163.com.
 **/

namespace Ipf\Router;

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Ipf\Utils\ConfigChecker;
use Ipf\Utils\ConfigLoader;

abstract class BaseRoute
{
    /**
     * @var \FastRoute\Dispatcher
     */
    protected static $dispatcher;

    protected static $initialized = false;

    public static function init()
    {
        if (empty(self::$initialized)) {
            $route = ConfigLoader::getConfig('route');
            ConfigChecker::checkRouteConfig($route);
            self::$dispatcher = simpleDispatcher(function (RouteCollector $r) use ($route) {
                foreach ($route as $item) {
                    $r->addRoute(
                        $item['method'],
                        $item['route'],
                        $item['handler']
                    );
                }
            });
            self::$initialized = true;
        }
    }

    abstract public static function dispatch($request, $response);
}
