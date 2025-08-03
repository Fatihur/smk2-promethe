# 📄 PRODUCT REQUIREMENT DOCUMENT (PRD)  
**Aplikasi Penentuan Konsentrasi Keahlian Berbasis PROMETHEE**  
**SMK Negeri 2 Sumbawa Besar**

---

## 1. Latar Belakang

SMK Negeri 2 Sumbawa Besar memiliki 13 jurusan keahlian yang harus dipilih oleh calon peserta didik baru. Untuk memastikan penempatan siswa sesuai dengan minat, bakat, dan potensi akademiknya, dibutuhkan sistem penentuan konsentrasi keahlian yang objektif dan transparan.

Metode **PROMETHEE (Preference Ranking Organization Method for Enrichment Evaluation)** dipilih karena mampu memberikan perangkingan yang konsisten berdasarkan kriteria penilaian yang telah ditentukan. Aplikasi ini dirancang untuk membantu proses seleksi PPDB dengan membagi siswa ke dalam dua kategori: **Khusus** dan **Umum**, dengan alur seleksi yang berjenjang dan otomatis.

---

## 2. Tujuan Aplikasi

- Memudahkan panitia PPDB dalam mengelola data siswa dan proses seleksi.
- Memberikan hasil penentuan konsentrasi keahlian yang objektif berdasarkan metode PROMETHEE.
- Menerapkan alur seleksi bertahap: **Kategori Khusus terlebih dahulu**, baru kemudian **Kategori Umum**.
- Mengotomatiskan perangkingan dan penempatan siswa berdasarkan hasil tes (wawancara & TPA).
- Menyediakan fitur validasi hasil oleh ketua jurusan dan pelaporan.

---

## 3. Fitur Utama

| No | Fitur | Deskripsi |
|----|-------|---------|
| 1 | Autentikasi Pengguna | Login untuk Admin, Panitia PPDB, dan 13 Ketua Jurusan dengan hak akses berbeda |
| 2 | Manajemen Jurusan | CRUD data jurusan (13 jurusan) |
| 3 | Manajemen Kriteria & Bobot | CRUD kriteria dan sub-kriteria beserta bobotnya |
| 4 | Manajemen Tahun Akademik | Hanya satu tahun akademik aktif, dapat diedit |
| 5 | Manajemen Data Siswa | CRUD data calon siswa baru |
| 6 | Input Nilai Siswa | Input nilai wawancara (psikotes & minat bakat) dan TPA |
| 7 | Penentuan Kategori Otomatis | Siswa dengan pilihan jurusan 1 = TAB/TSM → otomatis masuk **Kategori Khusus** |
| 8 | Proses PROMETHEE | Perhitungan perangkingan menggunakan metode PROMETHEE (tipe *usual*) |
| 9 | Alur Seleksi Bertahap | Kategori Khusus diproses terlebih dahulu, baru Umum |
| 10 | Penilaian Tahap 2 (Khusus) | Siswa peringkat 1–80 dari PROMETHEE Khusus masuk penilaian tahap 2 |
| 11 | Validasi Ketua Jurusan | Validasi hasil penentuan oleh ketua jurusan terkait |
| 12 | Pelaporan & Cetak Hasil | Cetak hasil perangkingan dan laporan statistik |
| 13 | Dashboard Status Proses | Menampilkan status proses seleksi (Khusus: belum selesai/belum mulai/selesai) |

---

## 4. Struktur Pengguna (Role)

| No | Role | Jumlah | Hak Akses |
|----|------|--------|----------|
| 1 | Admin | 1 | Kelola akun, jurusan, kriteria, tahun akademik |
| 2 | Panitia PPDB | 1 | Kelola data siswa, nilai, jalankan PROMETHEE, cetak laporan |
| 3 | Ketua Jurusan | 13 | Validasi hasil penentuan untuk jurusannya masing-masing |

---

## 5. Kebutuhan Fungsional (Functional Requirements)

| No | Fitur | Deskripsi |
|----|------|-----------|
| FR-01 | **Autentikasi Pengguna** | Admin, Panitia, dan Ketua Jurusan dapat login dengan username dan password. Sesi otomatis logout setelah 30 menit tidak aktif. |
| FR-02 | **Manajemen Jurusan (CRUD)** | Admin dapat menambah, mengedit, melihat, dan menghapus data 13 jurusan. |
| FR-03 | **Manajemen Kriteria & Bobot (CRUD)** | Admin dapat mengelola kriteria (contoh: Wawancara, TPA) dan sub-kriteria (Psikotes, Minat Bakat, dll), serta bobotnya. Bobot total = 100%. |
| FR-04 | **Manajemen Tahun Akademik** | Hanya satu tahun akademik yang aktif. Bisa diedit oleh admin, tetapi tidak boleh lebih dari satu aktif. |
| FR-05 | **Manajemen Data Siswa (CRUD)** | Panitia dapat mengelola data siswa. Field yang diisi: Nama, NISN, Pilihan Jurusan 1 & 2, dll. **No. Pendaftaran di-generate otomatis. Tahun akademik diambil dari data aktif.** |
| FR-06 | **Input Nilai Siswa** | Panitia memasukkan nilai wawancara (skala 0–100) dan TPA (skala 0–100) per siswa. |
| FR-07 | **Penentuan Kategori Otomatis** | Jika Pilihan Jurusan 1 adalah **Teknik Alat Berat (TAB)** atau **Teknik Sepeda Motor (TSM)** → otomatis masuk **Kategori Khusus**. Selain itu → **Kategori Umum**. |
| FR-08 | **Proses PROMETHEE (Khusus)** | Panitia dapat menjalankan PROMETHEE untuk kategori khusus. Bisa memilih jumlah kuota (contoh: 80 dari 100 pendaftar). Sistem menghasilkan perangkingan. |
| FR-09 | **Penilaian Tahap 2 (Khusus)** | 80 siswa teratas dari PROMETHEE Khusus masuk penilaian tahap 2. Ketua jurusan TAB/TSM memvalidasi: **Lulus**, **Lulus Pilihan Kedua**, atau **Tidak Lulus**. |
| FR-10 | **Penempatan Otomatis (Khusus Gagal)** | Siswa yang **tidak masuk 80 besar** → otomatis dipindahkan ke **Kategori Umum** dengan **Pilihan Jurusan 2** sebagai jurusan utama. |
| FR-11 | **Proses PROMETHEE (Umum)** | Hanya bisa dijalankan **setelah Kategori Khusus selesai**. Panitia menjalankan PROMETHEE untuk semua siswa di kategori umum (termasuk yang tereliminasi dari khusus). |
| FR-12 | **Validasi Ketua Jurusan (Umum)** | Ketua jurusan masing-masing dapat melihat hasil perangkingan siswa yang masuk jurusannya dan melakukan validasi akhir. |
| FR-13 | **Tampilan Hasil** | Panitia dan ketua jurusan dapat melihat hasil perangkingan, status penempatan, dan status validasi. |
| FR-14 | **Cetak Laporan & Statistik** | Panitia dapat mencetak hasil akhir, daftar lulus, dan statistik seleksi per jurusan. |
| FR-15 | **Dashboard Alur Seleksi** | Menampilkan status: <br> - Kategori Khusus: [Belum Dimulai / Sedang Berjalan / Selesai] <br> - Kategori Umum: [Tidak Aktif / Siap / Sedang Berjalan / Selesai] |

---

## 6. Alur Proses (Flow)

### 🔄 Alur Kategori Khusus (TAB/TSM)

```mermaid
graph TD
    A[Siswa daftar dengan pilihan 1: TAB/TSM] --> B{Otomatis masuk Kategori Khusus}
    B --> C[Panitia input nilai: Wawancara & TPA]
    C --> D[Jalankan PROMETHEE Khusus]
    D --> E[Pilih kuota (contoh: 80)]
    E --> F[Siswa peringkat 1-80: Masuk Penilaian Tahap 2]
    F --> G[Validasi oleh Ketua Jurusan: Lulus / Lulus Pilihan 2 / Tidak Lulus]
    G --> H[Lulus → Masuk TAB/TSM]
    G --> I[Lulus Pilihan 2 → Masuk jurusan pilihan 2]
    G --> J[Tidak Lulus → Gugur]
    E --> K[Siswa di luar 80 besar → Otomatis pindah ke Kategori Umum dengan pilihan jurusan 2]
    K --> L[Status: Menunggu proses Umum]


## Teknologi

### 📦 Framework

- Laravel 12

### 📦 Database

- MySQL 

### 📦 Templating

- Blade
- AdminLTE




# Langkah-Langkah Perhitungan PROMETHEE (Preferensi Usual)

1. **Siapkan Data Alternatif (Siswa) dan Nilai Kriterianya**  
   Contoh kriteria:  
   - TPA (skala 0–100)  
   - Psikotes (skala 1–5)  
   - Minat & Bakat (biner: 0 atau 1)

2. **Tentukan Bobot Kriteria**  
   - TPA: `0.5`  
   - Psikotes: `0.25`  
   - Minat & Bakat: `0.25`

3. **Hitung Selisih Antar Alternatif**  
   Untuk setiap pasangan alternatif A dan B, hitung:  
   
4. **Hitung Nilai Preferensi per Kriteria**  
Gunakan fungsi preferensi *Usual*:  
- Jika `d > 0` → `P(d) = 1`  
- Jika `d ≤ 0` → `P(d) = 0`

5. **Hitung Indeks Preferensi Global**  
Dihitung untuk semua kriteria.

6. **Hitung Leaving Flow (φ⁺) dan Entering Flow (φ⁻)**  
- **Leaving Flow (Outranking):**  
  ```
  φ⁺(A) = (1 / (n - 1)) × Σ π(A, j)  ; ∀ j ≠ A
  ```
- **Entering Flow (Inranking):**  
  ```
  φ⁻(A) = (1 / (n - 1)) × Σ π(j, A)  ; ∀ j ≠ A
  ```

7. **Hitung Net Flow (φ)**  

8. **Ranking Alternatif**  
Urutkan alternatif berdasarkan nilai **Net Flow (φ)** dari **tertinggi ke terendah**.  
Alternatif dengan nilai φ tertinggi merupakan pilihan terbaik.


##
