<?php

/**
 * User: isliang
 * Date: 2019-09-13
 * Time: 15:35
 * Email: yesuhuangsi@163.com.
 **/

namespace Ipf\Factory;

use Ipf\Utils\ConfigChecker;
use Ipf\Utils\ConfigLoader;
use Predis\Client;

class RedisFactory
{
    private static $instance = [];

    public static function getInstance($name = 'default')
    {
        return self::$instance[$name] ?
            self::$instance[$name] :
            (function () use ($name) {
                $config = ConfigLoader::getConfig('redis', $name);
                ConfigChecker::checkRedisConfig($name, $config);
                $client = new Client($config['client'], $config['options']);
                self::$instance[$name] = $client;

                return $client;
            })();
    }
}
