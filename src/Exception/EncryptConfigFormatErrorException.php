<?php

namespace Ipf\Exception;

use Ipf\Constant\ExceptionConst;

class EncryptConfigFormatErrorException extends IsfException
{
    public function __construct($name)
    {
        $code = ExceptionConst::CODE_ENCRYPT_CONFIG_FORMAT_ERROR;
        $message = "encrypt config {$name} format error";
        parent::__construct($message, $code);
    }
}
