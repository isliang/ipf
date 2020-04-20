<?php

namespace Ipf\Exception;

use Ipf\Constant\ExceptionConst;

class MemcachedConfigFormatErrorException extends IsfException
{
    public function __construct($name)
    {
        $code = ExceptionConst::CODE_MEMCACHED_CONFIG_FORMAT_ERROR;
        $message = "memcached config {$name} format error";
        parent::__construct($message, $code);
    }
}