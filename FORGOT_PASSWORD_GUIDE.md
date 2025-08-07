# Panduan Fitur Lupa Password

## Fitur yang Ditambahkan

### 1. **Halaman Login**
- Ditambahkan link "Lupa Password?" di bawah form login
- Link mengarah ke halaman reset password

### 2. **Halaman Profil**
- Ditambahkan tombol "Lupa Password" di bagian "Aksi Profil"
- Tombol membuka modal untuk mengirim link reset password

### 3. **Halaman Reset Password**
- Halaman untuk memasukkan email dan mengirim link reset
- Halaman untuk mengatur password baru dengan token

## Cara Menggunakan

### Dari Halaman Login:
1. Klik link "Lupa Password?" di halaman login
2. Masukkan email yang terdaftar
3. Klik "Kirim Link Reset Password"
4. Cek email untuk mendapatkan link reset
5. Klik link di email untuk mengatur password baru

### Dari Halaman Profil:
1. Login ke sistem
2. Buka halaman profil
3. Klik tombol "Lupa Password"
4. Email akan otomatis terisi (tidak bisa diubah)
5. Klik "Kirim Link Reset"
6. Cek email untuk mendapatkan link reset
7. Klik link di email untuk mengatur password baru

## Konfigurasi Email

### Untuk Testing (Default):
- Email akan disimpan di `storage/logs/laravel.log`
- Tidak ada email yang benar-benar dikirim

### Untuk Production:
Edit file `.env` dan tambahkan konfigurasi email:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@domain.com
MAIL_FROM_NAME="SMK2 PROMETHEE"
```

## Keamanan

### Token Reset Password:
- Token berlaku selama 60 menit
- Token hanya bisa digunakan sekali
- Token dihapus setelah password berhasil direset

### Throttling:
- Pengguna hanya bisa meminta reset password setiap 60 detik
- Mencegah spam email reset password

### Validasi:
- Email harus terdaftar di sistem
- Password baru minimal 8 karakter
- Konfirmasi password harus sesuai

## File yang Ditambahkan/Dimodifikasi

### Controllers:
- `app/Http/Controllers/Auth/ForgotPasswordController.php`
- `app/Http/Controllers/Auth/ResetPasswordController.php`

### Views:
- `resources/views/auth/passwords/email.blade.php`
- `resources/views/auth/passwords/reset.blade.php`
- `resources/views/auth/login.blade.php` (dimodifikasi)
- `resources/views/profile/show.blade.php` (dimodifikasi)

### Models:
- `app/Models/User.php` (dimodifikasi)

### Notifications:
- `app/Notifications/ResetPasswordNotification.php`

### Routes:
- `routes/web.php` (ditambahkan routes password reset)

## Testing

Untuk testing fitur ini:

1. **Test Email Logging:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Test Database:**
   ```sql
   SELECT * FROM password_reset_tokens;
   ```

3. **Test Manual:**
   - Akses `/password/reset`
   - Masukkan email yang valid
   - Cek log file untuk melihat email yang dikirim
   - Copy link dari log dan akses di browser
   - Set password baru

## Troubleshooting

### Email tidak dikirim:
- Pastikan konfigurasi email di `.env` benar
- Cek `storage/logs/laravel.log` untuk error
- Pastikan email pengguna terdaftar di database

### Token tidak valid:
- Token mungkin sudah kedaluwarsa (60 menit)
- Token mungkin sudah digunakan
- Minta token baru

### Error 404 pada link reset:
- Pastikan routes sudah terdaftar dengan `php artisan route:list`
- Clear cache dengan `php artisan route:clear`
