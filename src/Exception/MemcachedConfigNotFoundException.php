<?php
/**
 * User: isliang
 * Date: 2019-09-14
 * Time: 11:45
 * Email: yesuhuangsi@163.com.
 **/

namespace Ipf\Exception;

use Ipf\Constant\ExceptionConst;

class MemcachedConfigNotFoundException extends IsfException
{
    public function __construct($name)
    {
        $code = ExceptionConst::CODE_MEMCACHED_CONFIG_NOT_FOUND;
        $message = "memcached config {$name} not found";
        parent::__construct($message, $code);
    }
}
