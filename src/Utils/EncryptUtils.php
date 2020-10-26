<?php
namespace Ipf\Utils;

class EncryptUtils
{
    private static $config = [];

    /**
     * @param $str
     * @param $name
     * @return false|string
     * @throws \Ipf\Exception\ConfigPathUndefinedException
     * @throws \Ipf\Exception\EncryptConfigFormatErrorException
     * @throws \Ipf\Exception\EncryptConfigNotFoundException
     */
    public static function encrypt($str, $name)
    {
        list($cipher, $key, $iv) = self::getCipherAndKey($name);
        return openssl_encrypt($str, $cipher, $key, 0, $iv);
    }

    /**
     * @param $str
     * @param $name
     * @return false|string
     * @throws \Ipf\Exception\ConfigPathUndefinedException
     * @throws \Ipf\Exception\EncryptConfigFormatErrorException
     * @throws \Ipf\Exception\EncryptConfigNotFoundException
     */
    public static function decrypt($str, $name)
    {
        list($cipher, $key, $iv) = self::getCipherAndKey($name);
        return openssl_decrypt($str, $cipher, $key, 0, $iv);
    }

    /**
     * @param $name
     * @return array
     * @throws \Ipf\Exception\ConfigPathUndefinedException
     * @throws \Ipf\Exception\EncryptConfigFormatErrorException
     * @throws \Ipf\Exception\EncryptConfigNotFoundException
     */
    private static function getCipherAndKey($name)
    {
        if (empty(self::$config[$name])) {
            $config = ConfigLoader::getConfig('encrypt', $name);
            ConfigChecker::checkEncryptConfig($name, $config);
            self::$config[$name] = $config;
        }
        return [
            self::$config[$name]['cipher'],
            self::$config[$name]['key'],
            self::$config[$name]['iv'],
        ];
    }
}