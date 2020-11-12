<?php
namespace Ipf\Http\Request;

class FpmRequest extends RequestAbstract
{
    public function __construct()
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            $this->server[strtolower($key)] = $value;
            if (strpos($key, 'HTTP_') === 0) {
                $k = str_replace('_', '-', substr($key, 5));
                if ($k == 'COOKIE') {
                    $cookies = str_replace('; ','&', $value);
                    parse_str($cookies, $this->cookies);
                } else {
                    $headers[$k] = $value;
                }
            }
        }
        $this->request = new \GuzzleHttp\Psr7\Request(
            $this->server['request_method'],
            $this->server['request_scheme'] . '://' . $this->server['server_name'] . $this->server['request_uri'],
            $headers,
            file_get_contents('php://input')
        );
        //query param
        parse_str($this->request->getUri()->getQuery(), $this->query);
        //post param
        $post = (string)$this->request->getBody();
        switch ($this->getHeader('content-type')) {
            case 'application/x-www-form-urlencoded':
                parse_str($post, $this->post);
                break;
            case 'application/json':
                $this->post = json_decode($post, true);
                break;
        }
        //file
        $this->files = $_FILES;
    }
}