<?php
namespace Ipf\Http\Response;

class FpmResponse extends ResponseAbstract
{
    private $header = [];
    private $status = 200;

    public function status(int $status)
    {
        $this->status = $status;
    }

    public function header(string $key, string $value)
    {
        $this->header[$key] = $value;
    }

    public function cookie($name, $value = "", $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false)
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    public function redirect(string $url, int $code)
    {
        $this->sendHeader();
        header("Location:" . $url, true, $code);
    }

    public function send(string $data)
    {
        $this->sendHeader();
        echo $data;
    }

    private function sendHeader()
    {
        foreach ($this->header as $k => $v) {
            header("{$k}: {$v}");
        }
        $this->header = [];
    }
}