<?php
/**
 * User: isliang
 * Date: 2019-09-14
 * Time: 07:56
 * Email: yesuhuangsi@163.com.
 **/

namespace Ipf\Utils;

use Ipf\Exception\EncryptConfigFormatErrorException;
use Ipf\Exception\EncryptConfigNotFoundException;
use Ipf\Exception\LogConfigFormatErrorException;
use Ipf\Exception\LogConfigNotFoundException;
use Ipf\Exception\MemcachedConfigFormatErrorException;
use Ipf\Exception\MemcachedConfigNotFoundException;
use Ipf\Exception\MysqlConfigFormatErrorException;
use Ipf\Exception\MysqlConfigNotFoundException;
use Ipf\Exception\PdoConfigFormatErrorException;
use Ipf\Exception\PdoConfigNotFoundException;
use Ipf\Exception\RedisConfigFormatErrorException;
use Ipf\Exception\RedisConfigNotFoundException;
use Ipf\Exception\RouteConfigFormatErrorException;
use Ipf\Exception\RouteConfigNotFoundException;

class ConfigChecker
{
    public static function checkRouteConfig($route)
    {
        if (empty($route) || !is_array($route)) {
            throw new RouteConfigNotFoundException();
        }
        foreach ($route as $item) {
            if (!isset($item['method']) || !isset($item['route']) || !isset($item['handler'])) {
                throw new RouteConfigFormatErrorException();
            }
        }
    }

    public static function checkLogConfig($name, $param)
    {
        if (empty($param) || !is_array($param)) {
            throw new LogConfigNotFoundException($name);
        }
        if (!isset($param['name']) || !isset($param['file_path'])) {
            throw new LogConfigFormatErrorException();
        }
    }

    public static function checkPdoConfig($name, $param)
    {
        if (empty($param) || !is_array($param)) {
            throw new PdoConfigNotFoundException($name);
        }
        if (!isset($param['dsn']) || !isset($param['username']) || !isset($param['password'])) {
            throw new PdoConfigFormatErrorException();
        }
    }

    public static function checkMysqlConfig($name, $param)
    {
        if (empty($param) || !is_array($param)) {
            throw new MysqlConfigNotFoundException($name);
        }
        if (!isset($param['host']) || !isset($param['user']) || !isset($param['password']) || !isset($param['database'])) {
            throw new MysqlConfigFormatErrorException();
        }
    }

    public static function checkRedisConfig($name, $param)
    {
        if (empty($param) || !is_array($param)) {
            throw new RedisConfigNotFoundException($name);
        }
        if (!isset($param['client']) || !isset($param['options'])) {
            throw new RedisConfigFormatErrorException();
        }
    }

    public static function checkMemcachedConfig($name, $param)
    {
        if (empty($param) || !is_array($param)) {
            throw new MemcachedConfigNotFoundException($name);
        }
        foreach ($param as $item) {
            list($domain, $port, $weight) = $item;
            if (empty($domain) || empty($port)) {
                throw new MemcachedConfigFormatErrorException();
            }
        }
    }

    public static function checkEncryptConfig($name, $param)
    {
        if (empty($param) || !is_array($param)) {
            throw new EncryptConfigNotFoundException($name);
        }
        if (empty($param['cipher']) || empty($param['key']) || empty($param['iv'])) {
            throw new EncryptConfigFormatErrorException($name);
        }
    }
}
