# Input Nilai Siswa Per Jurusan

Fitur ini memungkinkan panitia PPDB untuk menginput nilai siswa berdasarkan jurusan dengan cara yang lebih terorganisir dan efisien.

## Fitur Utama

### 1. Dashboard Nilai Per Jurusan
- **URL**: `/panitia/nilai-jurusan`
- **Fungsi**: Menampilkan daftar semua jurusan dengan statistik progress input nilai
- **Informasi yang ditampilkan**:
  - Total siswa per jurusan
  - Jumlah siswa yang sudah dinilai
  - Progress percentage
  - Aksi untuk melihat detail siswa atau input massal

### 2. Daftar Siswa Per Jurusan
- **URL**: `/panitia/nilai-jurusan/{jurusan}/siswa`
- **Fungsi**: Menampilkan daftar siswa dalam jurusan tertentu
- **Informasi yang ditampilkan**:
  - Data siswa (No. Pendaftaran, NISN, Nama, Kategori)
  - Progress input nilai per siswa
  - Status kelengkapan nilai
  - Aksi untuk input/edit nilai individual

### 3. Input Nilai Individual
- **URL**: `/panitia/nilai-jurusan/{jurusan}/siswa/{siswa}/input`
- **Fungsi**: Form input nilai untuk satu siswa
- **Fitur**:
  - Validasi rentang nilai sesuai kriteria jurusan
  - Auto-focus dan keyboard navigation
  - Input keterangan untuk setiap kriteria
  - Validasi real-time

### 4. Input Nilai Massal (Bulk Input)
- **URL**: `/panitia/nilai-jurusan/{jurusan}/bulk`
- **Fungsi**: Input nilai untuk banyak siswa sekaligus dalam bentuk tabel
- **Fitur**:
  - Tabel responsif dengan semua siswa dan kriteria
  - Fill all function (isi semua field dengan nilai yang sama)
  - Clear all function (kosongkan semua field)
  - Counter field yang terisi
  - Keyboard navigation antar field
  - Validasi sebelum submit

## Struktur Database

### Tabel yang Terlibat
1. **master_kriteria**: Kriteria penilaian global
2. **kriteria_jurusan**: Mapping kriteria ke jurusan dengan rentang nilai
3. **nilai_siswa**: Nilai siswa untuk setiap kriteria
4. **siswa**: Data siswa
5. **jurusan**: Data jurusan

### Relasi
- `nilai_siswa.master_kriteria_id` → `master_kriteria.id`
- `nilai_siswa.siswa_id` → `siswa.id`
- `kriteria_jurusan.master_kriteria_id` → `master_kriteria.id`
- `kriteria_jurusan.jurusan_id` → `jurusan.id`

## Controller dan Route

### NilaiJurusanController
- `index()`: Dashboard jurusan
- `siswa(Jurusan $jurusan)`: Daftar siswa per jurusan
- `inputNilai(Jurusan $jurusan, Siswa $siswa)`: Form input individual
- `storeNilai(Request $request, Jurusan $jurusan, Siswa $siswa)`: Simpan nilai individual
- `bulkInput(Jurusan $jurusan)`: Form input massal
- `storeBulkNilai(Request $request, Jurusan $jurusan)`: Simpan nilai massal

### Route Group
```php
Route::prefix('nilai-jurusan')->name('nilai-jurusan.')->group(function () {
    Route::get('/', [NilaiJurusanController::class, 'index'])->name('index');
    Route::get('{jurusan}/siswa', [NilaiJurusanController::class, 'siswa'])->name('siswa');
    Route::get('{jurusan}/siswa/{siswa}/input', [NilaiJurusanController::class, 'inputNilai'])->name('input');
    Route::post('{jurusan}/siswa/{siswa}/store', [NilaiJurusanController::class, 'storeNilai'])->name('store');
    Route::get('{jurusan}/bulk', [NilaiJurusanController::class, 'bulkInput'])->name('bulk');
    Route::post('{jurusan}/bulk/store', [NilaiJurusanController::class, 'storeBulkNilai'])->name('bulk.store');
});
```

## Validasi

### Validasi Input Individual
- Setiap kriteria memiliki rentang nilai (`nilai_min` - `nilai_max`) yang berbeda
- Validasi dinamis berdasarkan kriteria jurusan
- Field nilai wajib diisi
- Keterangan opsional

### Validasi Input Massal
- Validasi yang sama seperti individual
- Field boleh kosong (tidak mengubah nilai yang sudah ada)
- Minimal satu field harus diisi sebelum submit

## Menu Navigasi

Menu ditambahkan di `MenuService.php` untuk role panitia:
```php
[
    'text' => 'Input Nilai Siswa',
    'icon' => 'fas fa-fw fa-edit',
    'submenu' => [
        [
            'text' => 'Per Jurusan',
            'route' => 'panitia.nilai-jurusan.index',
            'icon' => 'fas fa-fw fa-graduation-cap',
            'active' => ['panitia.nilai-jurusan.*'],
        ],
        [
            'text' => 'Individual',
            'route' => 'panitia.siswa.index',
            'icon' => 'fas fa-fw fa-user',
            'active' => ['panitia.siswa.nilai.*'],
        ],
    ],
],
```

## Keunggulan Fitur

1. **Terorganisir**: Input nilai berdasarkan jurusan memudahkan panitia
2. **Efisien**: Input massal menghemat waktu untuk banyak siswa
3. **User-friendly**: Interface yang intuitif dengan progress indicator
4. **Validasi Ketat**: Memastikan data yang diinput sesuai kriteria
5. **Responsive**: Dapat digunakan di berbagai ukuran layar
6. **Keyboard Navigation**: Mempercepat proses input data

## Cara Penggunaan

1. **Akses Menu**: Panitia → Input Nilai Siswa → Per Jurusan
2. **Pilih Jurusan**: Klik "Lihat Siswa" pada jurusan yang diinginkan
3. **Input Nilai**:
   - **Individual**: Klik tombol edit pada siswa tertentu
   - **Massal**: Klik "Input Massal" untuk input banyak siswa sekaligus
4. **Simpan**: Klik tombol simpan setelah mengisi nilai

## Auto-Setup Kriteria

Fitur ini dilengkapi dengan auto-setup kriteria yang akan otomatis membuat data kriteria default jika belum ada:

### Kriteria Default yang Dibuat:
1. **TPA** - Tes Potensi Akademik (TPA) (0-100)
2. **PSI** - Tes Psikologi (0-100)
3. **MNT** - Minat dan Bakat (0-100)
4. **TKD** - Kemampuan Teknik Dasar (0-100)
5. **MTK** - Nilai Matematika (0-100)
6. **IND** - Nilai Bahasa Indonesia (0-100)
7. **IPA** - Nilai IPA (0-100)

### Cara Kerja Auto-Setup:
- Ketika mengakses halaman input nilai untuk jurusan yang belum memiliki kriteria
- System otomatis membuat master kriteria dan mapping ke jurusan tersebut
- Semua kriteria dibuat dengan rentang nilai 0-100 (dapat diubah admin)
- Status aktif secara default

## Setup Manual (Opsional)

### Melalui Web Interface:
1. Akses halaman **Input Nilai Siswa** → **Per Jurusan**
2. Klik tombol **"Setup Data Test"** di pojok kanan atas
3. Data akan otomatis dibuat dan redirect ke halaman utama

### Melalui URL Langsung:
```
http://your-domain/panitia/nilai-jurusan/setup-data
```

### Melalui Command Line (jika tersedia):
```bash
php artisan setup:test-data
```

Setup ini akan membuat:
- **7 Master Kriteria** dengan rentang nilai 0-100
- **Mapping kriteria** ke semua jurusan aktif
- **2 Data siswa test** untuk testing

## Troubleshooting

### Error "Column not found: kriteria_jurusan_id"
- Pastikan menggunakan `master_kriteria_id` bukan `kriteria_jurusan_id`
- Jalankan migration terbaru

### Kriteria tidak muncul
- Sistem akan otomatis membuat kriteria default saat pertama kali akses
- Jika masih tidak muncul, cek log Laravel untuk error
- Pastikan tabel `master_kriteria` dan `kriteria_jurusan` ada

### Validasi gagal
- Periksa rentang nilai (`nilai_min` dan `nilai_max`) di tabel `kriteria_jurusan`
- Pastikan nilai yang diinput sesuai rentang yang ditetapkan

### Error "Duplicate Entry" / "Integrity Constraint Violation"
**Masalah:** Error SQLSTATE[23000] saat menyimpan nilai siswa
**Penyebab:** Constraint unique pada kombinasi `siswa_id` dan `master_kriteria_id`
**Solusi yang Diterapkan:**
- Sistem otomatis menghapus data lama sebelum menyimpan data baru
- Menggunakan transaction untuk memastikan konsistensi data
- Method `delete()` + `create()` menggantikan `updateOrCreate()`

### Tidak ada siswa
- Gunakan command `php artisan setup:test-data` untuk membuat data test
- Atau tambahkan siswa manual melalui menu Data Siswa
- Gunakan tombol **"Setup Data Test"** di halaman Input Nilai Per Jurusan

### Testing dan Development Tools
- Tombol **"Setup Data Test"**: Membuat data kriteria dan siswa untuk testing
- Tombol **"Clear Nilai"**: Menghapus semua data nilai siswa (berguna saat development)
- Kedua tombol tersedia di halaman utama Input Nilai Per Jurusan
