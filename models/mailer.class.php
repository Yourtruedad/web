<?php

class mailer 
{
    public function sendMail($recipient, $subject, $content) 
    {
        $mail = new PHPMailer;
        //$mail->SMTPDebug = 3; // Enable verbose debug output
        $mail->isSMTP();
        $mail->Host = CONFIG_EMAIL_NOTIFICATION_HOST;
        $mail->Port = CONFIG_EMAIL_NOTIFICATION_PORT;
        $mail->SMTPSecure = "none";
        $mail->SMTPAuth = true;
        $mail->Username = CONFIG_EMAIL_NOTIFICATION_USER;
        $mail->Password = CONFIG_EMAIL_NOTIFICATION_PASSWORD;

        $mail->setFrom('no-reply@everwintermu.com', 'EverWinter MU');
        $mail->addAddress($recipient);
        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body = $content;

        if($mail->send()) {
            return true;
        }
        return false;
    }
}