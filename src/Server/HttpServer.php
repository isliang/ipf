<?php
namespace Ipf\Server;

use Ipf\Exception\IsfException;
use Ipf\Router\FastRoute;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

class HttpServer
{
    /**
     * @var $server Server
     */
    protected static $server;

    public function __construct($host = '127.0.0.1', $port = 9501, $setting = [])
    {
        self::$server = new Server($host, $port);
        if (!empty($setting)) {
            self::$server->set($setting);
        }
        self::$server->on('request', [$this, 'onRequest']);
    }

    /**
     * @param $request Request
     * @param $response Response
     */
    function onRequest($request, $response)
    {
        try {
            FastRoute::dispatch($request, $response);
        } catch (IsfException $e) {
            $response->header("HTTP/1.0 404 Not Found");
            $response->end();
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