<?php
namespace Ipf\Http\Response;

interface ResponseInterface
{
    public function status(int $status);
    public function header(string $key, string $value);
    public function cookie($name, $value = "", $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false);
    public function redirect(string $url, int $code);
    public function send(string $data);
}