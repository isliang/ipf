<?php
namespace Ipf\Factory;

use GuzzleHttp\Psr7\Request;

class RequestFactory
{
    /**
     * @return Request
     */
    public function getInstance()
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headers[$key] = $value;
            }
        }
        $request = new Request(
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'],
            $headers,
            file_get_contents('php://input')
        );
        return $request;
    }
}