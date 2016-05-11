<?php
namespace Multiple\Library;
use Phalcon\Di;
use Multiple\Library\PHPMailer\smtp,
    Multiple\Library\PHPMailer\PHPMailer as PHPMailer;
/*[mail]
host         = 'smtp.yandex.ru'
username     = bca.bca.bca
password     = 'BCA22BCA22'
security     = tls
port         = 25
charset      = UTF-8
email        = bca.bca.bca@yandex.ru
from         = BCA*/

class Mailer {
    public static function get(){
        $di = DI::getDefault();
        $cf = $di->get("mail");
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->isHTML(true);
        $mail->SMTPAuth = true;
        $mail->Username = $cf['username'];
        $mail->Password = $cf['password'];
        //$mail->SMTPDebug = 1;
        $mail->CharSet = $cf['charset'];
        $mail->Host = $cf['host'];
        $mail->Port = $cf['port'];
        $mail->SMTPSecure = $cf['security'];
        $mail->SetFrom($cf['email'], $cf['from']);
        return $mail;
    }
}
