<?php
/**
 * User: isliang
 * Date: 2019-09-14
 * Time: 05:58
 * Email: yesuhuangsi@163.com
 **/

namespace Isf\Controller;

use Isf\Constant\CommConst;
use Swoole\Http\Request;
use Swoole\Http\Response;

class IsfController
{
    /**
     * @var $request Request
     */
    protected $request;

    /**
     * @var $response Response
     */
    protected $response;

    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    protected function json($params)
    {
        $this->response->header("Content-Type", "application/json");
        $content = json_encode($params);
        $size = CommConst::SIZE_RESPONSE_WRITE_BUFFER;
        while (strlen($content) > $size) {
            $this->response->write(substr($content, 0, $size));
            $content = substr($content, $size);
        }
        $this->response->write($content);
        $this->response->end();
    }
}
