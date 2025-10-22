# Max Gym Management - Render Deployment Guide

## Prerequisites
1. Akun GitHub
2. Akun Render.com
3. Database MySQL (bisa menggunakan Render Database atau penyedia lain)

## Langkah-langkah Deployment

### 1. Persiapan Repository GitHub
```bash
# Inisialisasi Git (sudah dilakukan)
git init
git add .
git commit -m "Initial commit for Render deployment"

# Buat repository baru di GitHub
# Kemudian push ke GitHub
git remote add origin https://github.com/YOUR_USERNAME/maxgymmanagement.git
git push -u origin master
```

### 2. Setup Database
1. Buat database MySQL di Render atau penyedia hosting database lainnya
2. Catat kredensial database (host, username, password, database name)

### 3. Deploy ke Render
1. Masuk ke [Render.com](https://render.com)
2. Klik "New" → "Web Service"
3. Connect your GitHub repository
4. Konfigurasi service:
   - **Name**: maxgymmanagement (atau nama pilihan Anda)
   - **Runtime**: PHP
   - **Build Command**: `composer install --no-dev --optimize-autoloader`
   - **Start Command**: `php spark serve --host 0.0.0.0 --port $PORT`
   - **Plan**: Free

### 4. Environment Variables
Set environment variables di Render dashboard:

```
CI_ENVIRONMENT=production
APP_ENV=production
DATABASE_HOST=your-database-host
DATABASE_NAME=your-database-name
DATABASE_USER=your-database-username
DATABASE_PASSWORD=your-database-password
APP_KEY=your-app-key-here
```

### 5. Update Base URL
Setelah deploy, update `app/Config/App.php`:
```php
public string $baseURL = 'https://your-app-name.onrender.com/';
```

### 6. Database Migration
Jalankan migration setelah aplikasi online:
```
https://your-app-name.onrender.com/index.php/migrate
```

## File Konfigurasi yang Telah Disiapkan
- `render.yaml` - Konfigurasi Render
- `.env.production` - Environment variables untuk production
- `app/Config/App.php` - Base URL untuk production

## Troubleshooting
1. **Error 500**: Periksa environment variables dan database connection
2. **Migration gagal**: Pastikan database credentials benar
3. **Assets tidak load**: Periksa baseURL configuration

## Catatan
- Pastikan mengubah placeholder values dengan nilai sebenarnya
- Untuk production, gunakan HTTPS dan secure credentials
- Monitor logs di Render dashboard untuk debugging
