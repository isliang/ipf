<?php
/**
 * User: isliang
 * Date: 2019-09-14
 * Time: 07:38
 * Email: yesuhuangsi@163.com.
 **/

namespace Ipf\Exception;

use Ipf\Constant\ExceptionConst;

class RouteConfigNotFoundException extends IsfException
{
    public function __construct()
    {
        $code = ExceptionConst::CODE_ROUTE_CONFIG_NOT_FOUND;
        $message = 'route config not found';
        parent::__construct($message, $code);
    }
}
