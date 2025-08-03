# Fitur Import Data Siswa

## Overview
Fitur import memungkinkan panitia untuk mengupload data siswa secara massal menggunakan file Excel (.xlsx, .xls) atau CSV (.csv). Template disediakan dalam format Excel (.xlsx) untuk memudahkan penggunaan.

## Akses Fitur
- **URL:** `/panitia/siswa-import`
- **Menu:** Data Siswa → Tombol "Import Excel"
- **Role:** Panitia

## Format File Import

### Kolom yang Diperlukan (8 Field Saja)
| Kolom | Wajib | Deskripsi | Contoh |
|-------|-------|-----------|---------|
| `pilihan_ke_1` | **Ya** | Nama atau kode jurusan pilihan pertama | "Teknik Alat Berat" atau "TAB" |
| `pilihan_ke_2` | **Tidak** | Nama atau kode jurusan pilihan kedua (boleh kosong) | "Teknik Sepeda Motor" atau "" |
| `nisn` | **Ya** | Nomor Induk Siswa Nasional (10 digit) | "1234567890" |
| `nama_calon_peserta_didik` | **Ya** | Nama lengkap siswa | "Ahmad Budi Santoso" |
| `tempat_tgl_lahir` | Tidak | Format: "Tempat, DD-MM-YYYY" | "Jakarta, 15-05-2008" |
| `asal_sekolah` | Tidak | Nama sekolah asal | "SMP Negeri 1 Jakarta" |
| `nama_ayah` | Tidak | Nama ayah siswa | "Budi Santoso" |
| `alamat` | Tidak | Alamat lengkap | "Jl. Merdeka No. 123, Jakarta" |

### Contoh Data
```csv
pilihan_ke_1,pilihan_ke_2,nisn,nama_calon_peserta_didik,tempat_tgl_lahir,asal_sekolah,nama_ayah,alamat
Teknik Alat Berat,Teknik Sepeda Motor,1234567890,Ahmad Budi Santoso,"Jakarta, 15-05-2008",SMP Negeri 1 Jakarta,Budi Santoso,"Jl. Merdeka No. 123, Jakarta"
TSM,,1234567891,Siti Dewi Sari,"Bandung, 20-03-2008",SMP Negeri 2 Bandung,Agus Sari,"Jl. Sudirman No. 456, Bandung"
Teknik Alat Berat,,1234567892,Dedi Kurniawan,"Yogyakarta, 12-09-2008",SMP Negeri 5 Yogyakarta,Sari Gunawan,"Jl. Malioboro No. 654, Yogyakarta"
```

**Catatan:** Pilihan ke-2 boleh dikosongkan seperti pada contoh baris 2 dan 3.

## Cara Menggunakan

### 1. Download Template
1. Akses halaman import siswa
2. Klik tombol **"Download Template"**
3. Template Excel (.xlsx) akan berisi:
   - **Sheet 1 "Template":** Header kolom dan contoh data
   - **Sheet 2 "Daftar Jurusan":** Daftar semua jurusan yang tersedia
   - Format yang sudah benar dan siap digunakan

### 2. Isi Data
1. Buka file template Excel (.xlsx) di Microsoft Excel atau aplikasi spreadsheet
2. Gunakan **Sheet "Template"** untuk mengisi data siswa
3. Lihat **Sheet "Daftar Jurusan"** untuk referensi jurusan yang tersedia
4. Isi data sesuai contoh yang sudah ada
5. **Pilihan ke-2 boleh dikosongkan**
6. Simpan file dalam format Excel (.xlsx) atau CSV (.csv)

### 3. Upload File
1. Klik tombol **"Pilih file..."**
2. Pilih file yang sudah diisi
3. Klik **"Import Data"**
4. Tunggu proses import selesai

## Validasi dan Aturan

### Validasi Otomatis
- **NISN:** Harus 10 digit, unik
- **Nama:** Wajib diisi, maksimal 255 karakter
- **Jurusan:** Harus sesuai dengan jurusan yang tersedia
- **Format File:** Maksimal 2MB

### Penanganan Data
- **NISN Duplikat:** Data akan diperbarui (update)
- **Jurusan Tidak Ditemukan:** Baris akan dilewati
- **Data Kosong:** Baris akan dilewati
- **Format Tanggal Salah:** Akan menggunakan default 01-01-2008

### Kategori Otomatis
- **Khusus:** Jika pilihan pertama adalah TAB atau TSM
- **Umum:** Untuk jurusan lainnya

### Jenis Kelamin Otomatis
Sistem akan mendeteksi jenis kelamin berdasarkan nama:
- **Perempuan:** Nama mengandung "siti", "dewi", "sri", "nia", "ani", dll.
- **Laki-laki:** Nama mengandung "ahmad", "muhammad", "budi", "andi", dll.
- **Default:** Laki-laki jika tidak dapat dideteksi

## Hasil Import

### Statistik Import
Setelah import selesai, sistem akan menampilkan:
- **Berhasil:** Jumlah data yang berhasil diimport
- **Dilewati:** Jumlah baris yang dilewati (data kosong/invalid)
- **Error:** Jumlah error yang terjadi
- **Gagal Validasi:** Jumlah data yang gagal validasi

### Data yang Dibuat Otomatis
Untuk setiap siswa yang berhasil diimport, sistem akan otomatis mengisi:
- **No. Pendaftaran:** Dibuat otomatis (format: YYYY0001, YYYY0002, dst.)
- **Jenis Kelamin:** Auto-detect berdasarkan nama
- **Kategori:** Auto-assign berdasarkan pilihan jurusan 1 (TAB/TSM = khusus, lainnya = umum)
- **Tahun Akademik:** Menggunakan tahun akademik yang aktif
- **Status Seleksi:** Default "pending"

**Field yang tidak perlu diisi manual:**
- No. Pendaftaran (auto-generate)
- Jenis Kelamin (auto-detect)
- Kategori (auto-assign)
- Tahun Akademik (auto-assign)
- Status (auto-assign)

## Troubleshooting

### File Tidak Bisa Diupload
- Periksa format file (harus .xlsx, .xls, atau .csv)
- Periksa ukuran file (maksimal 2MB)
- Pastikan file tidak corrupt

### Data Tidak Terimport
- Periksa kolom wajib sudah terisi
- Pastikan nama jurusan sesuai dengan yang tersedia
- Periksa format NISN (harus 10 digit)

### Jurusan Tidak Ditemukan
- Download template terbaru untuk melihat daftar jurusan
- Gunakan nama lengkap atau kode jurusan yang tepat
- Pastikan jurusan dalam status aktif

### Error Validasi
- Periksa format tanggal lahir
- Pastikan NISN unik (tidak duplikat dalam file)
- Periksa panjang karakter nama (maksimal 255)

## Tips dan Best Practices

### Persiapan Data
1. **Bersihkan Data:** Hapus spasi berlebih, karakter khusus
2. **Konsistensi:** Gunakan format yang sama untuk semua baris
3. **Backup:** Simpan file asli sebelum import

### Optimasi Import
1. **Batch Kecil:** Import dalam batch 100-500 siswa
2. **Test Dulu:** Test dengan 5-10 data terlebih dahulu
3. **Periksa Log:** Monitor log Laravel jika ada error

### Setelah Import
1. **Verifikasi Data:** Periksa data yang terimport
2. **Koreksi Manual:** Edit data yang perlu diperbaiki
3. **Backup Database:** Backup setelah import besar

## Jurusan yang Tersedia
Sistem akan menampilkan daftar jurusan aktif di halaman import. Pastikan menggunakan nama atau kode yang sesuai.

## Keamanan
- File upload dibatasi ukuran dan format
- Validasi ketat untuk mencegah data invalid
- Log semua aktivitas import
- Rollback otomatis jika terjadi error kritis
