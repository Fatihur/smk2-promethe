# Perubahan Sistem Kriteria: Dari Per-Jurusan ke Global

## Overview
Sistem kriteria telah diubah dari model per-jurusan menjadi global. Sekarang semua kriteria dikelola secara terpusat di master kriteria dengan pengaturan bobot dan rentang nilai yang berlaku untuk semua jurusan.

## Perubahan Database

### 1. Tabel `master_kriteria`
**Kolom Baru:**
- `bobot` (decimal 5,2): Bobot kriteria untuk perhitungan PROMETHEE
- `nilai_min` (decimal 8,2): Nilai minimum yang diperbolehkan
- `nilai_max` (decimal 8,2): Nilai maksimum yang diperbolehkan

### 2. Tabel `kriteria_jurusan`
**Status:** DIHAPUS
- Tabel ini tidak lagi diperlukan karena kriteria sekarang bersifat global

## Perubahan Model

### MasterKriteria Model
**Kolom Fillable Baru:**
```php
'bobot', 'nilai_min', 'nilai_max'
```

**Method Baru:**
- `getBobotFormattedAttribute()`: Format bobot untuk display
- `getNilaiRangeAttribute()`: Rentang nilai sebagai string
- `isNilaiValid($nilai)`: Validasi nilai dalam rentang
- `getActiveWithWeights()`: Ambil kriteria aktif dengan bobot

**Method Dihapus:**
- `jurusans()`: Relasi many-to-many dengan jurusan
- `kriteriaJurusan()`: Relasi dengan pivot table
- `getBobotForJurusan()`: Bobot per jurusan
- `isActiveForJurusan()`: Status aktif per jurusan

### Jurusan Model
**Method Diubah:**
- `getActiveKriteriasAttribute()`: Sekarang mengembalikan semua kriteria aktif global

**Method Dihapus:**
- `masterKriterias()`: Relasi many-to-many
- `kriteriaJurusan()`: Relasi dengan pivot table
- `activeKriterias()`: Kriteria aktif per jurusan

### KriteriaJurusan Model
**Status:** DIHAPUS - Model ini tidak lagi diperlukan

## Perubahan Controller

### 1. MasterKriteriaController
**Validasi Baru:**
```php
'bobot' => 'required|numeric|min:0.01|max:100',
'nilai_min' => 'required|numeric|min:0',
'nilai_max' => 'required|numeric|min:1|gt:nilai_min',
```

### 2. NilaiJurusanController
**Perubahan Utama:**
- Menggunakan `MasterKriteria::where('is_active', true)` instead of `KriteriaJurusan`
- Validasi menggunakan `nilai_min` dan `nilai_max` dari master kriteria
- Tidak lagi bergantung pada kriteria per jurusan

### 3. NilaiController
**Perubahan Utama:**
- Menggunakan master kriteria global untuk validasi
- Form input menggunakan rentang nilai dari master kriteria

### 4. KriteriaJurusanController
**Status:** DIHAPUS - Controller ini tidak lagi diperlukan

## Perubahan Service

### PrometheusService
**Perubahan Utama:**
- `calculateRanking()`: Menggunakan `MasterKriteria` langsung
- `calculateCriteriaWeights()`: Menggunakan bobot dari master kriteria
- Tidak lagi menggunakan `KriteriaJurusan` model

**Algoritma Bobot:**
```php
// Jika total bobot = 0, gunakan equal weights
if ($totalBobot == 0) {
    $weights[$k->id] = 1.0 / $totalKriteria;
} else {
    // Gunakan bobot dari master_kriteria, normalisasi ke sum = 1
    $weights[$k->id] = $k->bobot / $totalBobot;
}
```

## Perubahan View

### 1. Master Kriteria Forms
**Form Create/Edit:**
- Tambah field `bobot`, `nilai_min`, `nilai_max`
- Validasi client-side untuk rentang nilai

### 2. Master Kriteria Index
**Tabel:**
- Kolom "Penggunaan" dihapus
- Tambah kolom "Bobot" dan "Rentang Nilai"

### 3. Nilai Input Forms
**Perubahan:**
- Menggunakan `$masterKriteria` instead of `$kriteriaJurusan`
- Rentang nilai dari `$kriteria->nilai_min` dan `$kriteria->nilai_max`
- Validasi menggunakan atribut master kriteria

## Perubahan Route

### Route Dihapus:
```php
// Kriteria Jurusan Management routes
Route::get('jurusan/{jurusan}/kriteria', ...)
Route::post('jurusan/{jurusan}/kriteria', ...)
Route::patch('jurusan/{jurusan}/kriteria/{kriteria_jurusan}', ...)
Route::delete('jurusan/{jurusan}/kriteria/{kriteria_jurusan}', ...)
Route::patch('jurusan/{jurusan}/kriteria/{kriteria_jurusan}/toggle-status', ...)
```

## Migration

### File: `2025_08_03_120000_remove_kriteria_jurusan_add_to_master.php`
**Proses:**
1. Tambah kolom `bobot`, `nilai_min`, `nilai_max` ke `master_kriteria`
2. Migrate data dari `kriteria_jurusan` ke `master_kriteria`
3. Drop tabel `kriteria_jurusan`

## Keuntungan Perubahan

### 1. Simplifikasi Sistem
- Tidak perlu mengelola kriteria per jurusan
- Satu tempat untuk mengatur semua kriteria
- Konsistensi kriteria di seluruh jurusan

### 2. Kemudahan Maintenance
- Update kriteria cukup di satu tempat
- Tidak ada duplikasi data kriteria
- Lebih mudah untuk backup dan restore

### 3. Fleksibilitas PROMETHEE
- Bobot kriteria dapat diatur secara global
- Perhitungan PROMETHEE lebih konsisten
- Mudah untuk eksperimen dengan bobot berbeda

### 4. User Experience
- Interface lebih sederhana
- Tidak perlu setup kriteria per jurusan
- Proses input nilai lebih streamlined

## Testing

### Command Test:
```bash
php artisan promethee:calculate --kategori=all
```

**Result:**
- ✅ Berhasil menghitung ranking 54 siswa kategori khusus dengan kuota 10
- ✅ Berhasil menghitung ranking 407 siswa kategori umum
- ✅ Perhitungan PROMETHEE selesai!

### Browser Test:
- ✅ Halaman master kriteria dapat diakses
- ✅ Form create/edit kriteria berfungsi
- ✅ Input nilai siswa berfungsi
- ✅ Hasil PROMETHEE dapat dilihat

## Backward Compatibility

**Breaking Changes:**
- Tabel `kriteria_jurusan` dihapus
- Model `KriteriaJurusan` dihapus
- Controller `KriteriaJurusanController` dihapus
- Route kriteria jurusan dihapus

**Data Migration:**
- Data kriteria existing dipertahankan
- Nilai siswa existing tetap valid
- Hasil PROMETHEE sebelumnya tetap dapat diakses

## Next Steps

1. **Update Documentation**: Update user manual untuk workflow baru
2. **Training**: Berikan training kepada user tentang perubahan sistem
3. **Monitoring**: Monitor performa sistem setelah perubahan
4. **Backup**: Pastikan backup database sebelum deploy ke production
