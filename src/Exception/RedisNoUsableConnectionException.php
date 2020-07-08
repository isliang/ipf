<?php
/**
 * User: isliang
 * Date: 2019/10/16
 * Time: 16:02
 * Email: wslhdu@163.com.
 **/

namespace Ipf\Exception;

use Ipf\Constant\ExceptionConst;

class RedisNoUsableConnectionException extends IsfException
{
    public function __construct()
    {
        $code = ExceptionConst::CODE_REDIS_NO_USABLE_CONNECTION_ERROR;
        $message = 'redis no usable connection';
        parent::__construct($message, $code);
    }
}
