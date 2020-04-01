<?php
/**
 * User: isliang
 * Date: 2019/9/17
 * Time: 11:19
 * Email: wslhdu@163.com
 **/

namespace Ipf\Exception;

use Ipf\Constant\ExceptionConst;

class MysqlNoUsableConnectionException extends IsfException
{
    public function __construct()
    {
        $code = ExceptionConst::CODE_MYSQL_NO_USABLE_CONNECTION_ERROR;
        $message = "mysql no usable connection";
        parent::__construct($message, $code);
    }
}
