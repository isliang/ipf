<?php
namespace Ipf\Http;

use Ipf\Exception\MethodNotExistException;

/**
 * Class Request
 * @package Ipf\Http
 * @method getMethod()
 * @method getUri()
 */
class Request
{
    /**
     * @var \GuzzleHttp\Psr7\Request
     */
    private $request;

    /**
     * @var array
     */
    private $query = [];

    /**
     * @var array
     */
    private $post = [];

    private function __construct()
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headers[$key] = $value;
            }
        }
        $this->request = new \GuzzleHttp\Psr7\Request(
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'],
            $headers,
            file_get_contents('php://input')
        );
    }

    /**
     * @return Request
     */
    public static function getInstance()
    {
        static $instance = null;
        if (!$instance) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * @param null $name
     * @return array|mixed
     * 返回get参数
     */
    public function getQuery($name = null)
    {
        if (empty($this->query) && $query = $this->request->getUri()->getQuery()) {
            parse_str($query, $this->query);
        }
        return is_null($name) ? $this->query : $this->query[$name];
    }

    public function getPost($name = null)
    {
        $content_type = $this->request->getHeader('HTTP_CONTENT_TYPE')[0];
        switch ($content_type) {
            case 'application/x-www-form-urlencoded':
                if (empty($this->post) && $post = (string)$this->request->getBody()) {
                    parse_str($post, $this->post);
                }
                break;
            case 'application/json':
                if (empty($this->post) && $post = (string)$this->request->getBody()) {
                    $this->post = json_decode($post, true);
                }
                break;
            case 'multipart/form-data':
                if (empty($this->post) && $_FILES) {
                    $this->post = $_FILES;
                }
                break;
        }
        return is_null($name) ? $this->post : $this->post[$name];
    }

    public function getHeader($name = null)
    {
        return is_null($name) ? $this->request->getHeaders() :
            $this->request->getHeaderLine($name);
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @throws MethodNotExistException
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->request, $name)) {
            return call_user_func_array([$this->request, $name], $arguments);
        }

        throw new MethodNotExistException(get_called_class(), $name);
    }
}