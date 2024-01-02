<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/PHPMailer/phpmailer/src/Exception.php';
require 'vendor/PHPMailer/phpmailer/src/PHPMailer.php';
require 'vendor/PHPMailer/phpmailer/src/SMTP.php';
$mail = new PHPMailer(true);


require_once "vendor/autoload.php";
$mail = new PHPMailer;
$mail->SMTPDebug = 3;                           
$mail->isSMTP();                           
$mail->Host = "smtp.mailtrap.io";
$mail->SMTPAuth = true;                      
$mail->Username = "827bc655cd97fa";             
$mail->Password = "a6ef68e3d01464";                       
$mail->SMTPSecure = "tls";                       
$mail->Port = 587;                    
$mail->From = "name@gmail.com";
$mail->FromName = "Full Name";
$mail->addAddress("name@example.com", "Recepient Name");
$mail->isHTML(true);
$mail->Subject = "Subject Text";
$mail->Body = "<i>Mail body in HTML</i>";
$mail->AltBody = "This is the plain text version of the email content";
if(!$mail->send())
{
echo "Mailer Error: " . $mail->ErrorInfo;
}
else
{
echo "Message has been sent successfully";
}