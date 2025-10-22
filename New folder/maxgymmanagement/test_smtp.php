<?php
// Simple SMTP test using PHP's mail function
echo "Testing Gmail SMTP configuration...\n\n";

// Email configuration
$to = 'syamsirclas@gmail.com'; // Ganti dengan email Anda yang valid
$subject = 'Test Email - MaxGym Management';
$message = '<h1>Test Email</h1><p>This is a test email to verify Gmail SMTP configuration.</p>';
$headers = [
    'MIME-Version: 1.0',
    'Content-type: text/html; charset=UTF-8',
    'From: MaxGym Management <maxgymperformance9@gmail.com>',
    'Reply-To: syamsirclas@gmail.com',
    'X-Mailer: PHP/' . phpversion()
];

// Additional headers for SMTP
ini_set('SMTP', 'smtp.gmail.com');
ini_set('smtp_port', '587');
ini_set('sendmail_from', 'maxgymperformance9@gmail.com');

// Test using mail() function
$result = mail($to, $subject, $message, implode("\r\n", $headers));

if ($result) {
    echo "✅ Email sent successfully using PHP mail() function!\n";
    echo "Check your inbox at: $to\n";
} else {
    echo "❌ Email failed to send using PHP mail() function.\n";
    echo "Error: " . error_get_last()['message'] . "\n";
}

// Alternative test using fsockopen for SMTP
echo "\nTesting direct SMTP connection...\n";

$smtp_host = 'smtp.gmail.com';
$smtp_port = 587;

$socket = fsockopen($smtp_host, $smtp_port, $errno, $errstr, 30);

if (!$socket) {
    echo "❌ Cannot connect to SMTP server: $errstr ($errno)\n";
} else {
    echo "✅ Successfully connected to SMTP server\n";

    // Read server response
    $response = fgets($socket, 515);
    echo "Server response: $response";

    // Send EHLO
    fwrite($socket, "EHLO localhost\r\n");
    $response = fgets($socket, 515);
    echo "EHLO response: $response";

    fclose($socket);
}

echo "\nTest completed.\n";
echo "If email was sent successfully, check your spam folder if you don't see it in inbox.\n";
