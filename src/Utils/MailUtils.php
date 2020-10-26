<?php
namespace Ipf\Utils;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class MailUtils
{
    /**
     * @param $subject
     * @param $msg
     * @return bool
     * @throws \Ipf\Exception\ConfigPathUndefinedException
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public static function mail($subject, $msg)
    {
        $mail = new PHPMailer(true);
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail_config = ConfigLoader::getConfig('mail');
        $mail->isSMTP();
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->Host = $mail_config['host'];
        $mail->Port = $mail_config['port'];
        $mail->SMTPAuth = true;
        $mail->Username = $mail_config['user'];
        $mail->Password = $mail_config['pass'];
        $mail->setFrom($mail_config['user'], $mail_config['username']);
        foreach ($mail_config['to'] as $value) {
            $mail->addAddress($value['address'], $value['name']);
        }
        $mail->Subject = $subject;
        $mail->msgHTML($msg);
        return $mail->send();
    }
}