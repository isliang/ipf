<?php
/**
 * User: isliang
 * Date: 2019-09-14
 * Time: 07:41
 * Email: yesuhuangsi@163.com.
 **/

namespace Ipf\Exception;

use Ipf\Constant\ExceptionConst;

class LogConfigNotFoundException extends IsfException
{
    public function __construct($name)
    {
        $code = ExceptionConst::CODE_LOG_CONFIG_NOT_FOUND;
        $message = "log config {$name} not found";
        parent::__construct($message, $code);
    }
}
