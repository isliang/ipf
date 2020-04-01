<?php
/**
 * User: isliang
 * Date: 2019-09-14
 * Time: 05:57
 * Email: yesuhuangsi@163.com
 **/

namespace Isf\Controller;

use Isf\Constant\ExceptionConst;
use Isf\Exception\IsfException;
use Isf\Server\HttpServer;

class DispatchErrorController extends IsfController
{
    public function run(IsfException $e)
    {
        $this->logger->error($e->getCode() . ':' . $e->getMessage());
        switch ($e->getCode()) {
            case ExceptionConst::CODE_ROUTE_NOT_FOUND:
            case ExceptionConst::CODE_REQUEST_METHOD_NOT_ALLOWED:
                if ($this->request->server['request_uri'] == '/favicon.ico') {
                    $this->response->status(200);
                    $this->response->end();
                } elseif (defined("ENABLE_SERVER_COMMAND")) {
                    if ($this->request->server['request_uri'] == '/reload') {
                        $this->response->status(200);
                        $server = HttpServer::getServer();
                        $server->reload();
                        $this->response->end();
                    } elseif ($this->request->server['request_uri'] == '/ping') {
                        $this->response->status(200);
                        $this->response->end('pong');
                    } elseif ($this->request->server['request_uri'] == '/shutdown') {
                        $this->response->status(200);
                        $server = HttpServer::getServer();
                        $server->shutdown();
                        $this->response->end();
                    }
                }  else {
                    $this->response->status(404);
                    $this->response->end($e->getMessage());
                }
                break;
            case ExceptionConst::CODE_CLASS_NOT_FOUND:
            case ExceptionConst::CODE_METHOD_NOT_FOUND:
            default:
                $this->response->status(500);
                $this->response->end($e->getMessage());
        }
    }
}
