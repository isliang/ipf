<?php
namespace Ipf\Http\Request;

use Ipf\Utils\EnvUtils;
use Swoole\Http\Request;

class RequestFactory
{
    /**
     * @param Request|null $request
     * @return RequestInterface|null
     */
    public static function getRequest($request = null)
    {
        if (EnvUtils::isSwooleEnv()) {
            $instance = new SwooleRequest($request);
        } elseif (PHP_SAPI == 'fpm-fcgi') {
            $instance = new FpmRequest();
        } else {
            $instance = null;
        }
        return $instance;
    }
}