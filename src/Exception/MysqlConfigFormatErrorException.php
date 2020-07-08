<?php
/**
 * User: isliang
 * Date: 2019/9/17
 * Time: 11:15
 * Email: wslhdu@163.com.
 **/

namespace Ipf\Exception;

use Ipf\Constant\ExceptionConst;

class MysqlConfigFormatErrorException extends IsfException
{
    public function __construct()
    {
        $code = ExceptionConst::CODE_MYSQL_CONFIG_FORMAT_ERROR;
        $message = 'mysql config format error';
        parent::__construct($message, $code);
    }
}
