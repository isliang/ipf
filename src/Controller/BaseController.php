<?php
namespace Ipf\Controller;

use Ipf\Http\Request;

abstract class BaseController
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * BaseController constructor.
     * @param $request Request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }
}