<?php
/**
 * User: isliang
 * Date: 2019-09-13
 * Time: 15:13
 * Email: yesuhuangsi@163.com.
 **/

namespace Ipf\Exception;

use Ipf\Constant\ExceptionConst;

class ClassNotFoundException extends IsfException
{
    public function __construct($class)
    {
        $code = ExceptionConst::CODE_CLASS_NOT_FOUND;
        $message = "class {$class} not found";
        parent::__construct($message, $code);
    }
}
