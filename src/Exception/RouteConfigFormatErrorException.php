<?php
/**
 * User: isliang
 * Date: 2019-09-14
 * Time: 07:59
 * Email: yesuhuangsi@163.com.
 **/

namespace Ipf\Exception;

use Ipf\Constant\ExceptionConst;

class RouteConfigFormatErrorException extends IsfException
{
    public function __construct()
    {
        $code = ExceptionConst::CODE_ROUTE_CONFIG_FORMAT_ERROR;
        $message = 'route config format error';
        parent::__construct($message, $code);
    }
}
