<?php
/**
 * User: isliang
 * Date: 2019/9/17
 * Time: 11:13
 * Email: wslhdu@163.com.
 **/

namespace Ipf\Exception;

use Ipf\Constant\ExceptionConst;

class MysqlConfigNotFoundException extends IsfException
{
    public function __construct($name)
    {
        $code = ExceptionConst::CODE_MYSQL_CONFIG_NOT_FOUND;
        $message = "mysql config {$name} format error";
        parent::__construct($message, $code);
    }
}
