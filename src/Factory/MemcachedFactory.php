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

class MemcachedFactory
{
    private static $instance = [];

    public static function getInstance($name = 'default')
    {
        return self::$instance[$name] ?
            self::$instance[$name] :
            (function () use ($name) {
                $config = ConfigLoader::getConfig('memcached', $name);
                ConfigChecker::checkMemcachedConfig($name, $config);
                $memcached = new \Memcached($name);
                $memcached->addServers($config);
                self::$instance[$name] = $memcached;

                return $memcached;
            })();
    }
}
