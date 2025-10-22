<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Telah Berakhir</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #636e72 0%, #2d3436 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
        .expired-box { background: #ffeaa7; border: 1px solid #d63031; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .member-info { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #636e72; }
        .footer { text-align: center; margin-top: 30px; color: #666; font-size: 12px; }
        .button { display: inline-block; background: #636e72; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .urgent { color: #d63031; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⏰ Membership Berakhir</h1>
            <p>Status membership Anda saat ini</p>
        </div>

        <div class="content">
            <h2>Halo, <?= esc($member['nama_member']) ?>!</h2>

            <div class="expired-box">
                <h3 class="urgent">❌ Membership Anda telah berakhir</h3>
                <p>Membership Anda telah berakhir pada tanggal <?= date('d F Y', strtotime($member['tanggal_expired'])) ?>.</p>
            </div>

            <div class="member-info">
                <h3>Detail Membership Anda:</h3>
                <p><strong>Nama:</strong> <?= esc($member['nama_member']) ?></p>
                <p><strong>No. Member:</strong> <?= esc($member['no_member']) ?></p>
                <p><strong>Tanggal Kadaluarsa:</strong> <span class="urgent"><?= date('d F Y', strtotime($member['tanggal_expired'])) ?></span></p>
                <p><strong>Status:</strong> <span class="urgent">TIDAK AKTIF</span></p>
                <p><strong>Tipe Member:</strong> <?= esc($member['type_member']) ?></p>
            </div>

            <p><strong>Dampak dari membership yang berakhir:</strong></p>
            <ul>
                <li>❌ Tidak dapat mengakses fasilitas gym</li>
                <li>❌ Tidak dapat mengikuti kelas fitness</li>
                <li>❌ Akses locker telah diblokir</li>
                <li>❌ Tidak dapat booking personal training</li>
                <li>❌ Tidak dapat menggunakan fasilitas tambahan</li>
            </ul>

            <p>Untuk mengaktifkan kembali membership Anda, silakan perpanjang di:</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="<?= base_url('admin/kasir') ?>" class="button">Perpanjang Membership Sekarang</a>
            </div>

            <p>Atau kunjungi langsung kantor MaxGym untuk perpanjangan manual.</p>

            <p><strong>Kontak Kami:</strong></p>
            <p>📧 Email: maxgym.performance@gmail.com<br>
            📱 WhatsApp: +62 815-6344-7530<br>
            📍 Alamat: Jl. Hj kokon Komariah, Subangjaya, Kec. Cikole,Sukabumi,Jawabarat<br>
            🕒 Jam Operasional: Senin - Minggu, 06:00 - 22:00</p>

            <p>Kami sangat menghargai dukungan Anda selama ini dan berharap dapat melayani Anda kembali dengan membership yang aktif.</p>

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
