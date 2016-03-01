<?php

class mailer 
{
    public function sendMail() {
        $mail = new PHPMailer;

        $mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = '185.49.13.28';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'admins';                 // SMTP username
        $mail->Password = '';                           // SMTP password
        $mail->SMTPSecure = 'STARTTLS';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 143;                                    // TCP port to connect to

        $mail->setFrom('from@example.com', 'Mailer');
        $mail->addAddress('p.przeworowski@gmail.com', 'Joe User');     // Add a recipient

        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Here is the subject';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {
            return 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        } else {
            return 'Message has been sent';
        }
    }
}