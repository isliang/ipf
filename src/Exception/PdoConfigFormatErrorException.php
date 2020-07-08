<?php
/**
 * User: isliang
 * Date: 2019-09-14
 * Time: 11:46
 * Email: yesuhuangsi@163.com.
 **/

namespace Ipf\Exception;

use Ipf\Constant\ExceptionConst;

class PdoConfigFormatErrorException extends IsfException
{
    public function __construct()
    {
        $code = ExceptionConst::CODE_PDO_CONFIG_FORMAT_ERROR;
        $message = 'pdo config format error';
        parent::__construct($message, $code);
    }
}
