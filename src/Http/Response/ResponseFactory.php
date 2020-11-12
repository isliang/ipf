<?php
namespace Ipf\Http\Response;

use Swoole\Http\Response;

class ResponseFactory
{
    /**
     * @param Response|null $response
     * @return ResponseInterface|null
     */
    public static function getInstance($response = null)
    {
        static $instance = null;
        if (!$instance) {
            if (PHP_SAPI == 'cli' && extension_loaded('swoole') &&
                version_compare(phpversion('swoole'), '4.3', '>')) {
                $instance = new SwooleResponse($response);
            } elseif (PHP_SAPI == 'fpm-fcgi') {
                $instance = new FpmResponse();
            }
        }
        return $instance;
    }
}