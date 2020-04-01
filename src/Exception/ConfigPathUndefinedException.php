<?php
/**
 * User: isliang
 * Date: 2019-09-13
 * Time: 14:44
 * Email: yesuhuangsi@163.com
 **/

namespace Ipf\Exception;

use Ipf\Constant\ExceptionConst;

class ConfigPathUndefinedException extends IsfException
{
    public function __construct()
    {
        $code = ExceptionConst::CODE_CONFIG_PATH_UNDEFINED;
        $message = "CONFIG_PATH undefined";
        parent::__construct($message, $code);
    }
}