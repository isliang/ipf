<?php
namespace Ipf\Http\Response;

use Swoole\Http\Response;

class ResponseFactory
{
    /**
     * @param Response|null $response
     * @return ResponseInterface|null
     */
    public static function getResponse($response = null)
    {
        $instance = null;
        if (PHP_SAPI == 'cli' && extension_loaded('swoole') &&
            version_compare(phpversion('swoole'), '4.3', '>')) {
            $instance = new SwooleResponse($response);
        } elseif (PHP_SAPI == 'fpm-fcgi') {
            $instance = new FpmResponse();
        } else {
            $instance = null;
        }
        return $instance;
    }
}