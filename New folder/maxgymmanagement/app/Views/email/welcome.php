<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di MaxGym Management</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .member-info { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #667eea; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
        .button { display: inline-block; background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Selamat Datang!</h1>
            <p>Terima kasih telah bergabung dengan MaxGym Management</p>
        </div>

        <div class="content">
            <h2>Halo, <?= esc($member['nama_member']) ?>!</h2>

            <p>Selamat datang di <strong>MaxGym Management</strong>! Kami sangat senang Anda telah bergabung dengan komunitas gym kami.</p>

            <div class="member-info">
                <h3>Informasi Member Anda:</h3>
                <p><strong>Nama:</strong> <?= esc($member['nama_member']) ?></p>
                <p><strong>No. Member:</strong> <?= esc($member['no_member']) ?></p>
                <p><strong>Tanggal Bergabung:</strong> <?= date('d F Y', strtotime($member['tanggal_join'])) ?></p>
                <p><strong>Tanggal Kadaluarsa:</strong> <?= date('d F Y', strtotime($member['tanggal_expired'])) ?></p>
                <p><strong>Tipe Member:</strong> <?= esc($member['type_member']) ?></p>
            </div>

            <p>Dengan menjadi member MaxGym, Anda mendapatkan akses penuh ke:</p>
            <ul>
                <li>✅ Fasilitas gym lengkap 24 jam</li>
                <li>✅ Kelas fitness terjadwal</li>
                <li>✅ Personal training (jika tersedia)</li>
                <li>✅ Locker room dan shower</li>
                <li>✅ Konsultasi nutrisi</li>
            </ul>

            <p><strong>PENTING:</strong> Simpan QR Code Anda untuk akses mudah ke gym. Anda dapat mengakses dashboard member melalui website kami.</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="<?= base_url('member/dashboard') ?>" class="button">Akses Dashboard Member</a>
            </div>

            <p>Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi kami di:</p>
            <p>📧 Email: maxgym.performance@gmail.com<br>
            📱 WhatsApp: +62 815-6344-7530<br>
            📍 Alamat: Jl. Hj kokon Komariah, Subangjaya, Kec. Cikole,Sukabumi,Jawabarat<br>
            🕒 Jam Operasional: Senin - Minggu, 06:00 - 22:00</p>

            <p>Semoga Anda menikmati pengalaman berolahraga bersama kami!</p>

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
