<?php

class mailer 
{
    public function sendMail($recipient, $subject, $content) 
    {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'utf-8';
        ini_set('default_charset', 'UTF-8');

        //$mail->SMTPDebug = 2; // Enable verbose debug output
        $mail->isSMTP();
        $mail->Host = CONFIG_EMAIL_NOTIFICATION_HOST;
        $mail->Port = CONFIG_EMAIL_NOTIFICATION_PORT;
        $mail->SMTPSecure = "none";
        $mail->SMTPAuth = true;
        $mail->Username = CONFIG_EMAIL_NOTIFICATION_USER;
        $mail->Password = CONFIG_EMAIL_NOTIFICATION_PASSWORD;
        $mail->SMTPOptions = array(
        'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->setFrom('no-reply@everwintermu.com', 'EverWinter MU');
        $mail->addAddress($recipient);
        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->msgHTML($content);

        if($mail->send()) {
            return true;
        }
        return false;
    }
}