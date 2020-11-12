<?php
namespace Ipf\Http\Request;

use Ipf\Utils\TSingleton;
use Swoole\Http\Request;

class SwooleRequest extends RequestAbstract
{
    use TSingleton;

    /**
     * SwooleRequest constructor.
     * @param Request $request
     */
    private function __construct($request = null)
    {
        $this->server = $request->server;
        $this->request = new \GuzzleHttp\Psr7\Request(
            $this->server['request_method'],
            $this->server['request_scheme'] . '://' . $this->server['server_name'] . $this->server['request_uri'],
            $request->header,
            $request->rawContent()
        );
        //query param
        $this->query = $request->get;
        //post param
        $this->post = $request->post;
        //cookie
        $this->cookies = $request->cookie;
        //file
        $this->files = $request->files;
    }
}