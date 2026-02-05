<?php
require_once __DIR__ . '/../../vendor/PHPMailer/PHPMailer.php';

use PHPMailer\PHPMailer\PHPMailer;

function send_mail($toEmail, $toName, $subject, $body)
{
    global $config;
    $smtp = $config['smtp'];

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = $smtp['host'];
    $mail->Port = $smtp['port'];
    $mail->Username = $smtp['user'];
    $mail->Password = $smtp['pass'];
    $mail->SMTPSecure = $smtp['encryption'];
    $mail->setFrom($smtp['from_email'], $smtp['from_name']);
    $mail->addAddress($toEmail, $toName);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AltBody = strip_tags($body);

    return $mail->send();
}
