<?php
/**
 * User: isliang
 * Date: 2019-09-14
 * Time: 08:01
 * Email: yesuhuangsi@163.com.
 **/

namespace Ipf\Exception;

use Ipf\Constant\ExceptionConst;

class LogConfigFormatErrorException extends IsfException
{
    public function __construct()
    {
        $code = ExceptionConst::CODE_LOG_CONFIG_FORMAT_ERROR;
        $message = 'log config format error';
        parent::__construct($message, $code);
    }
}
