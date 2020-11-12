<?php
namespace Ipf\Http\Response;

interface ResponseInterface
{
    /**
     * @param int $status
     * @return mixed
     */
    public function status($status);

    /**
     * @param string $key
     * @param string $value
     * @return mixed
     */
    public function header($key, $value);

    /**
     * @param $name
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     * @return mixed
     */
    public function cookie($name, $value = "", $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false);

    /**
     * @param string $url
     * @param int $code
     * @return mixed
     */
    public function redirect($url, $code);

    /**
     * @param string $data
     * @return mixed
     */
    public function send($data);
}