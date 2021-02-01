<?php
namespace Ipf\Http\Response;

use Ipf\Utils\EnvUtils;
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
        if (EnvUtils::isSwooleEnv()) {
            $instance = new SwooleResponse($response);
        } elseif (PHP_SAPI == 'fpm-fcgi') {
            $instance = new FpmResponse();
        } else {
            $instance = null;
        }
        return $instance;
    }
}