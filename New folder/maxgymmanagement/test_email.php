<?php
// Simple email test script using direct mail function
$to = 'syamsirbarvis@gmail.com';
$subject = 'Test Email from PHP Direct';
$message = '<h1>Test Email</h1><p>This is a test email sent directly from PHP mail function.</p>';
$headers = [
    'MIME-Version: 1.0',
    'Content-type: text/html; charset=UTF-8',
    'From: MaxGym Management <maxgymperformance9@gmail.com>',
    'Reply-To: maxgymperformance9@gmail.com'
];

$headersString = implode("\r\n", $headers);

echo "Testing email configuration to: $to\n";
if (mail($to, $subject, $message, $headersString)) {
    echo "Email sent successfully!\n";
} else {
    echo "Email failed to send.\n";
}
