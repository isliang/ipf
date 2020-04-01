<?php
/**
 * User: isliang
 * Date: 2019-09-13
 * Time: 15:14
 * Email: yesuhuangsi@163.com
 **/

namespace Ipf\Exception;

use Ipf\Constant\ExceptionConst;

class MethodNotExistException extends IsfException
{
    public function __construct($class, $method)
    {
        $code = ExceptionConst::CODE_METHOD_NOT_FOUND;
        $message = "class {$class} method {$method} not exist";
        parent::__construct($message, $code);
    }
}