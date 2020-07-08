<?php
/**
 * User: isliang
 * Date: 2019/10/16
 * Time: 15:59
 * Email: wslhdu@163.com.
 **/

namespace Ipf\Exception;

use Ipf\Constant\ExceptionConst;

class RedisConfigFormatErrorException extends IsfException
{
    public function __construct()
    {
        $code = ExceptionConst::CODE_REDIS_CONFIG_FORMAT_ERROR;
        $message = 'redis config format error';
        parent::__construct($message, $code);
    }
}
