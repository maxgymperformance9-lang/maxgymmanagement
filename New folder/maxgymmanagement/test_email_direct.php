<?php
// Simple direct email test using PHP mail
$to = 'syamsirclas@gmail.com';
$subject = 'Test Email from PHP Direct';
$message = '<h1>Test Email</h1><p>This is a test email sent directly from PHP mail function.</p>';
$headers = [
    'MIME-Version: 1.0',
    'Content-type: text/html; charset=UTF-8',
    'From: MaxGym Management <maxgymperformance9@gmail.com>',
    'Reply-To: maxgymperformance9@gmail.com'
];

$headersString = implode("\r\n", $headers);

if (mail($to, $subject, $message, $headersString)) {
    echo 'Email sent successfully using PHP mail()!';
} else {
    echo 'Failed to send email using PHP mail().';
}
