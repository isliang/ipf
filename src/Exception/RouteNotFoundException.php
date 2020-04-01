<?php
/**
 * User: isliang
 * Date: 2019-09-13
 * Time: 15:22
 * Email: yesuhuangsi@163.com
 **/

namespace Ipf\Exception;

use Ipf\Constant\ExceptionConst;

class RouteNotFoundException extends IsfException
{
    public function __construct($uri)
    {
        $code = ExceptionConst::CODE_ROUTE_NOT_FOUND;
        $message = "uri {$uri} not match any route";
        parent::__construct($message, $code);
    }
}