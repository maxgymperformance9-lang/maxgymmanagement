<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - MaxGym Management</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .warning-box { background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .reset-info { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #007bff; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
        .button { display: inline-block; background: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .urgent { color: #856404; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Reset Password</h1>
            <p>Permintaan reset password akun Anda</p>
        </div>

        <div class="content">
            <h2>Halo!</h2>

            <div class="warning-box">
                <h3 class="urgent">⚠️ Permintaan Reset Password</h3>
                <p>Anda menerima email ini karena ada permintaan untuk mereset password akun MaxGym Management Anda.</p>
            </div>

            <div class="reset-info">
                <h3>Langkah Selanjutnya:</h3>
                <p>Klik tombol di bawah ini untuk mereset password Anda. Link ini akan kadaluarsa dalam 1 jam untuk alasan keamanan.</p>

                <div style="text-align: center; margin: 30px 0;">
                    <a href="<?= esc($reset_link) ?>" class="button">Reset Password Sekarang</a>
                </div>

                <p><strong>PENTING:</strong> Jika Anda tidak meminta reset password, abaikan email ini. Password Anda akan tetap aman.</p>
            </div>

            <p>Jika tombol di atas tidak berfungsi, salin dan tempel link berikut ke browser Anda:</p>
            <p style="word-break: break-all; background: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace;"><?= esc($reset_link) ?></p>

            <p><strong>Tips Keamanan:</strong></p>
            <ul>
                <li>✅ Pastikan Anda menggunakan koneksi internet yang aman</li>
                <li>✅ Jangan bagikan link reset ini dengan orang lain</li>
                <li>✅ Buat password yang kuat dengan kombinasi huruf, angka, dan simbol</li>
                <li>✅ Gunakan password yang berbeda untuk akun yang berbeda</li>
            </ul>

            <p>Jika Anda mengalami kesulitan atau memiliki pertanyaan, hubungi tim support kami:</p>
            <p>📧 Email: support@maxgym.com<br>
            📱 WhatsApp: +62 812-3456-7890<br>
            📍 Alamat: Jl. Fitness No. 123, Jakarta</p>

            <p>Terima kasih atas perhatian Anda terhadap keamanan akun!</p>

            <p>Salam,<br>
            <strong>Tim MaxGym Management</strong></p>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis. Mohon jangan membalas email ini.</p>
            <p>&copy; 2024 MaxGym Management. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
