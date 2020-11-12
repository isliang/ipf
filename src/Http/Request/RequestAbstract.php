<?php
namespace Ipf\Http\Request;

use GuzzleHttp\Psr7\Request;
use Ipf\Exception\MethodNotExistException;

/**
 * Class RequestAbstract
 * @package Ipf\Http\Request
 */
abstract class RequestAbstract implements RequestInterface
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $query = [];

    /**
     * @var array
     */
    protected $post = [];

    protected $cookies = [];

    protected $files = [];

    protected $server = [];

    /**
     * @param null $name
     * @return array|mixed
     * 返回get参数
     */
    public function getQuery($name = null)
    {
        return is_null($name) ? $this->query : $this->query[$name];
    }

    public function getPost($name = null)
    {
        return is_null($name) ? $this->post : $this->post[$name];
    }

    public function getFile($name = null)
    {
        return is_null($name) ? $this->files : $this->files[$name];
    }

    public function getHeader($name = null)
    {
        return is_null($name) ? $this->request->getHeaders() :
            $this->request->getHeaderLine($name);
    }

    public function getIp()
    {
        $ip = $this->getHeader('client-ip') ?: $this->getHeader('x-forward-for') ?: $this->server['remote_addr'];
        $arr = explode(',', $ip);
        $ip = trim(end($arr));
        $ip = long2ip(ip2long($ip));
        return $ip;
    }

    public function getCookie($name = null)
    {
        return is_null($name) ? $this->cookies : $this->cookies[$name];
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