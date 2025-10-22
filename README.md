# Aplikasi Web Sistem Absensi Sekolah Berbasis QR Code

[![Continuous Integration](https://github.com/ikhsan3adi/absensi-sekolah-qr-code/actions/workflows/ci.yml/badge.svg)](https://github.com/ikhsan3adi/absensi-sekolah-qr-code/actions/workflows/ci.yml)
![GitHub Repo stars](https://img.shields.io/github/stars/ikhsan3adi/absensi-sekolah-qr-code?style=social)
![GitHub watchers](https://img.shields.io/github/watchers/ikhsan3adi/absensi-sekolah-qr-code?style=social)
![GitHub forks](https://img.shields.io/github/forks/ikhsan3adi/absensi-sekolah-qr-code?style=social)

![Preview](./screenshots/hero.png)

Aplikasi Web Sistem Absensi Sekolah Berbasis QR Code adalah sebuah proyek yang bertujuan untuk mengotomatisasi proses absensi di lingkungan sekolah menggunakan teknologi QR code. Aplikasi ini dikembangkan dengan menggunakan framework CodeIgniter 4 dan didesain untuk mempermudah pengelolaan dan pencatatan kehadiran penjaga dan pegawai.

> [Instalasi & Cara Penggunaan](#cara-penggunaan)

## Fitur Utama

- **QR Code scanner.** Setiap penjaga/pegawai menunjukkan qr code kepada perangkat yang dilengkapi dengan kamera. Aplikasi akan memvalidasi QR code dan mencatat kehadiran penjaga ke dalam database.
- **Notifikasi Presensi via WhatsApp**. Setelah berhasil scan dan presensi, pemberitahuan dikirim ke nomor hp penjaga melalui whatsapp.
- **Login petugas.**
- **Dashboard petugas.** Petugas sekolah dapat dengan mudah memantau kehadiran penjaga dalam periode waktu tertentu melalui tampilan yang disediakan.
- **QR Code generator & downloader.** Petugas yang sudah login akan men-generate dan/atau mendownload qr code setiap penjaga/pegawai. Setiap penjaga akan diberikan QR code unik yang terkait dengan identitas penjaga. QR code ini akan digunakan saat proses absensi.
- **Ubah data absen penjaga/pegawai.** Petugas dapat mengubah data absensi setiap penjaga/pegawai. Misalnya mengubah data kehadiran dari `tanpa keterangan` menjadi `sakit` atau `izin`.
- **Tambah, Ubah, Hapus(CRUD) data penjaga/pegawai.**
- **Tambah, Ubah, Hapus(CRUD) data di.**
- **Lihat, Tambah, Ubah, Hapus(CRUD) data petugas.** (khusus petugas yang login sebagai **`superadmin`**).
- **Generate Laporan.** Generate laporan dalam bentuk pdf.
- **Import Banyak penjaga.** Menggunakan CSV delimiter koma (,), Contoh: [CSV](https://github.com/ikhsan3adi/absensi-sekolah-qr-code/blob/141ef728f01b14b89b43aee2c0c38680b0b60528/public/assets/file/csv_siswa_example.csv).

> [!NOTE]
>
> ## Framework dan Library Yang Digunakan
>
> - [CodeIgniter 4](https://github.com/codeigniter4/CodeIgniter4)
> - [Material Dashboard Bootstrap 4](https://www.creative-tim.com/product/material-dashboard-bs4)
> - [Myth Auth Library](https://github.com/lonnieezell/myth-auth)
> - [Endroid QR Code Generator](https://github.com/endroid/qr-code)
> - [ZXing JS QR Code Scanner](https://github.com/zxing-js/library)
>
> ---
>
> - [Fonnte](https://fonnte.com/) - WhatsApp API untuk mengirim pesan notifikasi

## Screenshots

### Tampilan Halaman QR Scanner

![QR Scanner view](./screenshots/qr-scanner.jpeg)

### Tampilan Absen Masuk dan Pulang

![QR Scanner absen](./screenshots/absen.jpg)

> #### Notifikasi via WhatsApp
>
> ![Notifikasi WA](./screenshots/notif-wa.png)

### Tampilan Login Petugas

![Login](./screenshots/login.jpeg)

### Tampilan Dashboard Petugas

![Dashboard](./screenshots/dashboard.png)

### Tampilan CRUD Data Absen

| penjaga (Dengan Data di)                          |                       pegawai                       |
| -------------------------------------------------- | :----------------------------------------------: |
| ![CRUD Absen penjaga](./screenshots/absen-penjaga.png) | ![CRUD Absen pegawai](./screenshots/absen-pegawai.png) |

### Tampilan Ubah Data kehadiran

<p align="center">
  <img src="./screenshots/ubah-kehadiran.jpeg" height="320px" style="object-fit:cover" alt="Ubah Data kehadiran" title="Ubah Data kehadiran">
</p>

### Tampilan CRUD Data penjaga & pegawai

| penjaga                                            |                      pegawai                      |
| ------------------------------------------------ | :--------------------------------------------: |
| ![CRUD Data penjaga](./screenshots/data-penjaga.png) | ![CRUD Data pegawai](./screenshots/data-pegawai.png) |

### Tampilan CRUD Data di & wilayah

![CRUD Data penjaga](./screenshots/di-wilayah.png)

### Tampilan Generate QR Code dan Generate Laporan

| Generate QR                                   |                Generate Laporan                |
| --------------------------------------------- | :--------------------------------------------: |
| ![Generate QR](./screenshots/generate-qr.png) | ![Generate Laporan](./screenshots/laporan.png) |

## Cara Penggunaan

### Persyaratan

- [Composer](https://getcomposer.org/).
- PHP 8.1+ dan MySQL/MariaDB atau [XAMPP](https://www.apachefriends.org/download.html) versi 8.1+ dengan mengaktifkan extension `intl` dan `gd`.
- Pastikan perangkat memiliki kamera/webcam untuk menjalankan qr scanner. Bisa juga menggunakan kamera HP dengan bantuan software DroidCam.

### Instalasi

- Clone/Download source code proyek ini.

- Install dependencies yang diperlukan dengan cara menjalankan perintah berikut di terminal:

  ```shell
  composer install
  ```

- Jika belum terdapat file `.env`, rename file `.env.example` menjadi `.env`

- Buat database `db_absensi`(sesuaikan dengan yang terdapat di `.env`) di phpMyAdmin / mysql

- Jalankan migrasi database untuk membuat struktur tabel yang diperlukan. Ketikkan perintah berikut di terminal:

  ```shell
  php spark migrate --all
  ```

- Jalankan web server (contoh Apache, XAMPP, etc)
- Atau gunakan `php spark serve` (atur baseURL di `.env` menjadi `http://localhost:8080/` terlebih dahulu).
- Lalu jalankan aplikasi di browser.
- Login menggunakan krendensial superadmin:

  ```txt
  username : superadmin
  password : superadmin
  ```

- Izinkan akses kamera.

### Konfigurasi

> [!IMPORTANT]
>
> - Konfigurasi file `.env` untuk mengatur base url(terutama jika melakukan hosting), koneksi database dan pengaturan lainnya sesuai dengan lingkungan pengembangan Anda.
>
> - Untuk mengaktifkan **notifikasi WhatsApp**, pertama-tama ubah variabel `.env` berikut dari `false` menjadi `true`.
>
>   ```sh
>   # .env
>   # WA_NOTIFICATION=false # sebelum
>   WA_NOTIFICATION=true
>   ```
>
>   Lalu masukkan token WhatsApp API.
>
>   ```sh
>   # .env
>   WA_NOTIFICATION=true
>   WHATSAPP_PROVIDER=Fonnte
>   WHATSAPP_TOKEN=XXXXXXXXXXXXXXXXX # ganti dengan token anda
>   ```
>
>   _**Untuk mendapatkan token, daftar di website [fonnte](https://md.fonnte.com/new/register.php) terlebih dahulu. Lalu daftarkan device anda dan [dapatkan token Fonnte Whatsapp API](https://docs.fonnte.com/token-api-key/)**_
>
> - Untuk mengubah konfigurasi nama sekolah, tahun ajaran logo sekolah dll sudah disediakan pengaturan (khusus untuk superadmin).
>
> - Logo Sekolah Rekomendasi 100x100px atau 1:1 dan berformat PNG/JPG.
>
> - Jika ingin mengubah email, username & password dari superadmin, buka file `app\Database\Migrations\2023-08-18-000004_AddSuperadmin.php` lalu ubah & sesuaikan kode berikut:
>
>   ```php
>   // INSERT INITIAL SUPERADMIN
>   $email = 'adminsuper@gmail.com';
>   $username = 'superadmin';
>   $password = 'superadmin';
>   ```

## Kesimpulan

Dengan aplikasi web sistem absensi sekolah berbasis QR code ini, diharapkan proses absensi di sekolah menjadi lebih efisien dan terotomatisasi. Proyek ini dapat diadaptasi dan dikembangkan lebih lanjut sesuai dengan kebutuhan dan persyaratan sekolah Anda.

Jangan lupa beri star ya...⭐⭐⭐

## Contributing 🤝

Kami menerima kontribusi dari komunitas terbuka untuk meningkatkan aplikasi ini. Jika Anda menemukan masalah, bug, atau memiliki saran untuk peningkatan, silakan buat issue baru dalam repositori ini atau ajukan pull request.

## Donasi ❤

[![Donate saweria](https://img.shields.io/badge/Donate-Saweria-red?style=for-the-badge&link=https%3A%2F%2Fsaweria.co%2Fxiboxann)](https://saweria.co/xiboxann)

## Kontributor 🛠️

- [@ikhsan3adi](https://www.github.com/ikhsan3adi)
- [@reactmore](https://www.github.com/reactmore)
- [@janglapuk](https://www.github.com/janglapuk)
- [@nanda443](https://www.github.com/nanda443)
- [@kevindoni](https://www.github.com/kevindoni)
- [@pandigresik](https://github.com/pandigresik)
