<?php
namespace Ipf\Factory;

use GuzzleHttp\Psr7\Response;

class ResponseFactory
{
    /**
     * @return Response
     */
    public function getInstance()
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headers[$key] = $value;
            }
        }
        $response = new Response();
        return $response;
    }
}