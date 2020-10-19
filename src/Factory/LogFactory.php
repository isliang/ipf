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
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LogFactory
{
    private static $logger_list = [];

    /**
     * @param $name
     * @param bool $reload
     *
     * @return Logger
     */
    public static function getInstance($name, $reload = false)
    {
        $today = date('Ymd');

        return self::$logger_list[$name][$today] && empty($reload) ?
            self::$logger_list[$name][$today] :
            (function () use ($name, $reload, $today) {
                $config = ConfigLoader::getConfig('log', $name, $reload);
                ConfigChecker::checkLogConfig($name, $config);
                $logger = new Logger($config['name']);
                $logger->pushHandler(
                    new StreamHandler(
                        $config['file_path'].'.'.$today,
                        Logger::INFO
                    )
                );
                self::$logger_list[$name] = [];
                self::$logger_list[$name][$today] = $logger;

                return $logger;
            })();
    }
}
