<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 600px; margin: 0 auto; }
        .button { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Test Email Functionality</h1>
        <p>Click the button below to test sending a welcome email:</p>
        <a href="<?= base_url('test-email/send-test') ?>" class="button">Send Test Email</a>
        <br><br>
        <p><strong>Note:</strong> Make sure to update the test email address in the controller to your actual email for testing.</p>
    </div>
</body>
</html>
