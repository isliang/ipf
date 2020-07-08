<?php
/**
 * User: isliang
 * Date: 2019-09-14
 * Time: 11:45
 * Email: yesuhuangsi@163.com.
 **/

namespace Ipf\Exception;

use Ipf\Constant\ExceptionConst;

class PdoConfigNotFoundException extends IsfException
{
    public function __construct($name)
    {
        $code = ExceptionConst::CODE_PDO_CONFIG_NOT_FOUND;
        $message = "pdo config {$name} format error";
        parent::__construct($message, $code);
    }
}
