<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Perpanjangan Membership</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .success-box { background: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .member-info { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #28a745; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
        .button { display: inline-block; background: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .success { color: #155724; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Membership Berhasil Diperpanjang!</h1>
            <p>Terima kasih atas kepercayaan Anda</p>
        </div>

        <div class="content">
            <h2>Halo, <?= esc($member['nama_member']) ?>!</h2>

            <div class="success-box">
                <h3 class="success">🎉 Membership Anda telah berhasil diperpanjang</h3>
                <p>Selamat! Membership Anda telah aktif kembali dan siap digunakan.</p>
            </div>

            <div class="member-info">
                <h3>Detail Membership Baru Anda:</h3>
                <p><strong>Nama:</strong> <?= esc($member['nama_member']) ?></p>
                <p><strong>No. Member:</strong> <?= esc($member['no_member']) ?></p>
                <p><strong>Tanggal Bergabung:</strong> <?= date('d F Y', strtotime($member['tanggal_join'])) ?></p>
                <p><strong>Tanggal Kadaluarsa Baru:</strong> <span class="success"><?= date('d F Y', strtotime($member['tanggal_expired'])) ?></span></p>
                <p><strong>Tipe Member:</strong> <?= esc($member['type_member']) ?></p>
                <p><strong>Status:</strong> <span class="success">AKTIF</span></p>
            </div>

            <p><strong>Fasilitas yang dapat Anda nikmati kembali:</strong></p>
            <ul>
                <li>✅ Akses penuh ke fasilitas gym 24 jam</li>
                <li>✅ Kelas fitness terjadwal</li>
                <li>✅ Personal training (jika tersedia)</li>
                <li>✅ Locker room dan shower</li>
                <li>✅ Konsultasi nutrisi</li>
            </ul>

            <p>Simpan QR Code Anda untuk akses mudah ke gym. Anda dapat mengakses dashboard member melalui website kami.</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="<?= base_url('member/dashboard') ?>" class="button">Akses Dashboard Member</a>
            </div>

            <p>Jika Anda memiliki pertanyaan atau membutuhkan bantuan, jangan ragu untuk menghubungi kami di:</p>
            <p>📧 Email: maxgym.performance@gmail.com<br>
            📱 WhatsApp: +62 815-6344-7530<br>
            📍 Alamat: Jl. Hj kokon Komariah, Subangjaya, Kec. Cikole,Sukabumi,Jawabarat<br>
            🕒 Jam Operasional: Senin - Minggu, 06:00 - 22:00</p>

            <p>Terima kasih atas kepercayaan Anda kepada MaxGym Management. Kami berkomitmen untuk memberikan pengalaman terbaik dalam perjalanan fitness Anda!</p>

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
