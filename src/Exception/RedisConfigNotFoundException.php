<?php
/**
 * User: isliang
 * Date: 2019/10/16
 * Time: 15:58
 * Email: wslhdu@163.com
 **/

namespace Ipf\Exception;

use Ipf\Constant\ExceptionConst;

class RedisConfigNotFoundException extends IsfException
{
    public function __construct($name)
    {
        $code = ExceptionConst::CODE_REDIS_CONFIG_NOT_FOUND;
        $message = "redis config {$name} format error";
        parent::__construct($message, $code);
    }
}