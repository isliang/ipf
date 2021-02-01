<?php
namespace Ipf\Utils;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class EnvUtils
{
    public static function isSwooleEnv()
    {
        return PHP_SAPI == 'cli' &&
            extension_loaded('swoole') &&
            version_compare(phpversion('swoole'), '4.3', '>');
    }
}