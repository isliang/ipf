<?php
namespace Ipf\Http\Request;

use Swoole\Http\Request;

class SwooleRequest extends RequestAbstract
{
    /**
     * SwooleRequest constructor.
     * @param Request $request
     */
    public function __construct($request = null)
    {
        $this->server = $request->server;
        $this->request = new \GuzzleHttp\Psr7\Request(
            $this->server['request_method'],
            $this->server['request_uri'],
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

    public function getMethod()
    {
        return $this->request->getMethod();
    }

    public function getUri()
    {
        return $this->request->getUri();
    }
}