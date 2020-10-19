<?php
namespace Ipf\Controller;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

abstract class BaseController
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * BaseController constructor.
     * @param $request Request
     * @param $response Response
     */
    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
}