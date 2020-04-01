<?php
/**
 * User: isliang
 * Date: 2019-09-13
 * Time: 15:23
 * Email: yesuhuangsi@163.com
 **/

namespace Ipf\Exception;

use Ipf\Constant\ExceptionConst;

class RequestMethodNotAllowedException extends IsfException
{
    public function __construct($method, $uri)
    {
        $code = ExceptionConst::CODE_REQUEST_METHOD_NOT_ALLOWED;
        $message = "request method {$method} not allowed for the uri {$uri}";
        parent::__construct($message, $code);
    }
}