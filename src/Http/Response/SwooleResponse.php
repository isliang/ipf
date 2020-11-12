<?php
namespace Ipf\Http\Response;

use Ipf\Constant\CommConst;
use Ipf\Utils\TSingleton;
use Swoole\Http\Response;

class SwooleResponse extends ResponseAbstract
{
    use TSingleton;

    /**
     * @var Response
     */
    private $response;

    /**
     * SwooleResponse constructor.
     * @param null|Response $response
     */
    private function __construct($response = null)
    {
        $this->response = $response;
    }

    public function status(int $status)
    {
        $this->response->status($status);
    }

    public function header(string $key, string $value)
    {
        $this->response->header($key, $value);
    }

    public function cookie($name, $value = "", $expire = 0, $path = "", $domain = "", $secure = false, $httponly = false)
    {
        $this->response->cookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    public function redirect(string $url, int $code)
    {
        $this->response->redirect($url, $code);
    }

    public function send(string $data)
    {
        $size = CommConst::SIZE_RESPONSE_WRITE_BUFFER;
        while (strlen($data) > $size) {
            $this->response->write(substr($data, 0, $size));
            $data = substr($data, $size);
        }
        $this->response->write($data);
        $this->response->end();
    }
}