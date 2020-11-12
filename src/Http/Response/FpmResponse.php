<?php
namespace Ipf\Http\Response;

class FpmResponse extends ResponseAbstract
{
    private $header = [];
    private $status = 200;

    /**
     * @param int $status
     * @return mixed|void
     */
    public function status($status)
    {
        $this->status = $status;
    }

    /**
     * @param string $key
     * @param string $value
     * @return mixed|void
     */
    public function header($key, $value)
    {
        $this->header[$key] = $value;
    }

    /**
     * @param $name
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     * @return mixed|void
     */
    public function cookie($name, $value = "", $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false)
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    /**
     * @param string $url
     * @param int $code
     * @return mixed|void
     */
    public function redirect($url, $code)
    {
        $this->sendHeader();
        header("Location:" . $url, true, $code);
    }

    /**
     * @param string $data
     * @return mixed|void
     */
    public function send($data)
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