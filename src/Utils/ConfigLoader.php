<?php
/**
 * User: isliang
 * Date: 2019-09-13
 * Time: 14:53
 * Email: yesuhuangsi@163.com.
 **/

namespace Ipf\Utils;

use Ipf\Exception\ConfigPathUndefinedException;

class ConfigLoader
{
    /**
     * @var array
     */
    private static $config;

    /**
     * @param $filename
     *
     * @throws ConfigPathUndefinedException
     */
    private static function loadConfig($filename)
    {
        if (!defined('CONFIG_PATH')) {
            throw new ConfigPathUndefinedException();
        }
        $config = [];
        $file = rtrim(CONFIG_PATH, '/').'/'.strtolower($filename);
        if (file_exists($file.'.php')) {
            require $file.'.php';
        } elseif (file_exists($file.'.json')) {
            $config = json_decode(file_get_contents($file.'.json'), true);
        }
        self::$config[$filename] = $config;
    }

    /**
     * @param string      $filename
     * @param string|null $key
     * @param bool        $reload
     *
     * @throws ConfigPathUndefinedException
     *
     * @return mixed|null
     */
    public static function getConfig(string $filename, string $key = null, bool $reload = false)
    {
        if ($reload || !isset(self::$config[$filename])) {
            self::loadConfig($filename);
        }
        if (is_null($key)) {
            return self::$config[$filename];
        }

        return self::$config[$filename][$key] ?? null;
    }
}
