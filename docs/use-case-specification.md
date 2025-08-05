# Use Case Specification - Sistem PPDB SMK

## Actors

### 1. Admin
- **Deskripsi**: Administrator sistem yang memiliki akses penuh untuk mengelola konfigurasi sistem
- **Tanggung Jawab**: Mengelola master data, user management, monitoring sistem

### 2. Panitia
- **Deskripsi**: Tim panitia PPDB yang bertanggung jawab menjalankan proses seleksi
- **Tanggung Jawab**: Input data siswa, input nilai, menjalankan perhitungan PROMETHEE

### 3. Ketua Jurusan
- **Deskripsi**: Kepala jurusan yang bertugas memvalidasi hasil seleksi
- **Tanggung Jawab**: Validasi siswa yang masuk kuota kategori khusus

## Use Cases

### Authentication Package

#### UC1: Login
- **Actor**: Admin, Panitia, Ketua Jurusan
- **Deskripsi**: User melakukan login ke sistem
- **Precondition**: User memiliki akun yang valid
- **Flow**: 
  1. User memasukkan email dan password
  2. Sistem memvalidasi kredensial
  3. Sistem mengarahkan ke dashboard sesuai role

#### UC2: Logout
- **Actor**: Admin, Panitia, Ketua Jurusan
- **Deskripsi**: User keluar dari sistem
- **Flow**: User mengklik logout, sistem menghapus session

#### UC3: Manage Profile
- **Actor**: Admin, Panitia, Ketua Jurusan
- **Deskripsi**: User mengelola profil pribadi
- **Flow**: User dapat mengubah data profil seperti nama, email

#### UC4: Change Password
- **Actor**: Admin, Panitia, Ketua Jurusan
- **Deskripsi**: User mengubah password
- **Flow**: User memasukkan password lama dan baru

### Admin Management Package

#### UC5: Manage Dashboard
- **Actor**: Admin
- **Deskripsi**: Admin melihat dashboard overview sistem
- **Flow**: Menampilkan statistik siswa, status proses seleksi

#### UC6: Manage Jurusan
- **Actor**: Admin
- **Deskripsi**: Admin mengelola data jurusan
- **Flow**: CRUD operations untuk data jurusan (kode, nama, kuota)

#### UC7: Manage Master Kriteria
- **Actor**: Admin
- **Deskripsi**: Admin mengelola kriteria penilaian
- **Flow**: CRUD operations untuk kriteria (TPA, Psikotes, dll)

#### UC8: Manage Kriteria Jurusan
- **Actor**: Admin
- **Deskripsi**: Admin mengatur kriteria per jurusan
- **Flow**: Menentukan kriteria mana yang digunakan per jurusan

#### UC9: Manage Tahun Akademik
- **Actor**: Admin
- **Deskripsi**: Admin mengelola tahun akademik
- **Flow**: CRUD operations tahun akademik, set active year

#### UC10: Manage Users
- **Actor**: Admin
- **Deskripsi**: Admin mengelola user sistem
- **Flow**: CRUD operations user, assign roles

#### UC11: Monitor Selection Process
- **Actor**: Admin
- **Deskripsi**: Admin memantau status proses seleksi
- **Flow**: Melihat progress setiap tahap seleksi

#### UC12: View Final Results
- **Actor**: Admin
- **Deskripsi**: Admin melihat hasil akhir seleksi
- **Flow**: Menampilkan ranking dan status penerimaan siswa

### Panitia Management Package

#### UC15: Manage Siswa Data
- **Actor**: Panitia
- **Deskripsi**: Panitia mengelola data siswa pendaftar
- **Flow**: CRUD operations data siswa

#### UC16: Import Siswa from Excel
- **Actor**: Panitia
- **Deskripsi**: Import data siswa dari file Excel
- **Flow**: Upload file Excel, validasi data, import ke database

#### UC18: Input Nilai Individual
- **Actor**: Panitia
- **Deskripsi**: Input nilai siswa satu per satu
- **Flow**: Pilih siswa, input nilai per kriteria

#### UC19: Input Nilai Per Jurusan
- **Actor**: Panitia
- **Deskripsi**: Input nilai untuk semua siswa dalam satu jurusan
- **Flow**: Pilih jurusan, tampilkan form input nilai batch

#### UC21: Run PROMETHEE Khusus
- **Actor**: Panitia
- **Deskripsi**: Menjalankan perhitungan PROMETHEE kategori khusus
- **Precondition**: Data siswa dan nilai lengkap
- **Flow**: 
  1. Set kuota kategori khusus
  2. Jalankan algoritma PROMETHEE
  3. Generate ranking dan status kuota

#### UC22: Run PROMETHEE Umum
- **Actor**: Panitia
- **Deskripsi**: Menjalankan perhitungan PROMETHEE kategori umum
- **Precondition**: Proses khusus selesai dan tervalidasi
- **Flow**: Jalankan PROMETHEE untuk kategori umum

#### UC23: Transfer Failed Khusus to Umum
- **Actor**: Panitia
- **Deskripsi**: Memindahkan siswa khusus yang gagal ke kategori umum
- **Flow**: Otomatis transfer siswa yang tidak masuk kuota khusus

### Validation Management Package

#### UC27: Validate Student Results
- **Actor**: Ketua Jurusan
- **Deskripsi**: Validasi siswa yang masuk kuota kategori khusus
- **Flow**: 
  1. Lihat daftar siswa yang masuk kuota
  2. Review data siswa
  3. Approve/reject siswa

#### UC28: Bulk Validation
- **Actor**: Ketua Jurusan
- **Deskripsi**: Validasi multiple siswa sekaligus
- **Flow**: Select multiple siswa, bulk approve/reject

## Use Case Relationships

### Include Relationships
- UC21 include UC15: Perhitungan PROMETHEE memerlukan data siswa
- UC21 include UC20: Perhitungan PROMETHEE memerlukan nilai lengkap
- UC22 include UC27: PROMETHEE umum memerlukan validasi khusus selesai

### Extend Relationships
- UC16 extend UC15: Import Excel memperluas manage siswa
- UC19 extend UC18: Input per jurusan memperluas input individual
- UC28 extend UC27: Bulk validation memperluas validasi individual

## Alur Proses PROMETHEE

### 1. Tahap Persiapan Data
1. **Input Data Siswa** (UC15): Panitia memasukkan data siswa pendaftar
2. **Import Excel** (UC16): Alternatif import data siswa dari Excel
3. **Input Nilai** (UC18/UC19): Input nilai siswa per kriteria
4. **Validasi Kelengkapan** (UC5): Pastikan semua data lengkap

### 2. Tahap Perhitungan Kategori Khusus
1. **Set Kuota** (UC6): Tentukan kuota kategori khusus
2. **Run PROMETHEE Khusus** (UC21): Jalankan algoritma PROMETHEE
3. **Generate Ranking** (UC8): Hasilkan ranking berdasarkan phi net
4. **Tentukan Status Kuota** (UC9): Tandai siswa yang masuk/tidak masuk kuota

### 3. Tahap Validasi
1. **Review Results** (UC12): Ketua jurusan review hasil khusus
2. **Validasi Siswa** (UC27): Approve/reject siswa yang masuk kuota
3. **Bulk Validation** (UC28): Validasi multiple siswa sekaligus

### 4. Tahap Transfer dan Perhitungan Umum
1. **Transfer Siswa** (UC23): Pindahkan siswa khusus yang gagal ke umum
2. **Run PROMETHEE Umum** (UC22): Hitung ranking kategori umum
3. **Final Ranking** (UC11): Hasilkan ranking akhir

### 5. Tahap Pelaporan
1. **Generate Reports** (UC24): Buat laporan hasil seleksi
2. **Export Data** (UC26): Export ke Excel/PDF
3. **Print Reports** (UC25): Cetak laporan

## Business Rules

### BR1: Kuota Management
- Setiap jurusan memiliki kuota maksimal
- Kategori khusus memiliki kuota terpisah dari umum
- Siswa yang tidak masuk kuota khusus otomatis pindah ke umum

### BR2: Validation Rules
- Semua siswa kategori khusus yang masuk kuota harus divalidasi ketua jurusan
- PROMETHEE umum tidak dapat dijalankan sebelum validasi khusus selesai
- Ketua jurusan hanya dapat memvalidasi siswa di jurusannya

### BR3: PROMETHEE Calculation
- Minimal 2 siswa diperlukan untuk perhitungan PROMETHEE
- Semua kriteria harus memiliki nilai untuk setiap siswa
- Ranking berdasarkan nilai phi net tertinggi

### BR4: Data Integrity
- Setiap siswa harus memiliki pilihan jurusan 1
- Pilihan jurusan 2 bersifat opsional
- Nilai kriteria harus dalam rentang yang ditentukan
