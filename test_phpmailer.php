<?php
// Test PHPMailer directly
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'maxgymperformance9@gmail.com';
    $mail->Password   = 'czhzewvmdvevkqdu';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    // Recipients
    $mail->setFrom('maxgymperformance9@gmail.com', 'MaxGym Management');
    $mail->addAddress('miminmintar009@gmail.com');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from PHPMailer';
    $mail->Body    = '<h1>Test Email</h1><p>This is a test email sent using PHPMailer.</p>';

    $mail->send();
    echo 'Email sent successfully using PHPMailer!';
} catch (Exception $e) {
    echo "Email failed to send. Error: {$mail->ErrorInfo}";
}
