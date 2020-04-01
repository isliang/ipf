<?php
/**
 * User: isliang
 * Date: 2019-09-14
 * Time: 05:52
 * Email: yesuhuangsi@163.com
 **/

namespace Isf\Server;

use Isf\Controller\DispatchErrorController;
use Isf\Exception\IsfException;
use Isf\Router\FastRoute;
use Swoole\Http\Server;

class HttpServer
{
    /**
     * @var $server Server
     */
    protected static $server;

    public function __construct($host = '127.0.0.1', $port = 9501)
    {
        self::$server = new Server($host, $port);
        self::$server->on('request', [$this, 'onRequest']);
    }

    function onRequest($request, $response)
    {
        try {
            FastRoute::dispatch($request, $response);
        } catch (IsfException $e) {
            $controller = new DispatchErrorController($request, $response);
            $controller->run($e);
        }
    }

    public function start()
    {
        self::$server->start();
    }

    public static function getServer()
    {
        return self::$server;
    }
}
