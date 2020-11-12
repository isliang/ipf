<?php
namespace Ipf\Http\Request;

use Swoole\Http\Request;

class RequestFactory
{
    /**
     * @param Request|null $request
     * @return RequestInterface|null
     */
    public static function getInstance($request = null)
    {
        static $instance = null;
        if (!$instance) {
            if (PHP_SAPI == 'cli' && extension_loaded('swoole') &&
                version_compare(phpversion('swoole'), '4.3', '>')) {
                $instance = new SwooleRequest($request);
            } elseif (PHP_SAPI == 'fpm-fcgi') {
                $instance = new FpmRequest();
            }
        }
        return $instance;
    }
}