# Perubahan Semester Ganjil Only - Tahun Akademik

## Ringkasan Perubahan

Sistem telah diubah untuk hanya menggunakan semester "Ganjil" pada semua tahun akademik. Pilihan semester "Genap" telah dihapus dari seluruh sistem.

## File yang Dimodifikasi

### 1. **Validation Request**
**File:** `app/Http/Requests/TahunAkademikRequest.php`
- ✅ Menghapus validasi `semester` (tidak perlu lagi)
- ✅ Menghapus pesan error untuk semester

### 2. **Controller**
**File:** `app/Http/Controllers/Admin/TahunAkademikController.php`
- ✅ **Store method:** Otomatis set semester ke 'Ganjil'
- ✅ **Update method:** Otomatis set semester ke 'Ganjil'
- ✅ **Index method:** Menghapus filter semester
- ✅ **Allowed sorts:** Menghapus 'semester' dari sorting

### 3. **Views - Create Form**
**File:** `resources/views/admin/tahun-akademik/create.blade.php`
- ✅ Mengganti dropdown semester dengan input readonly "Ganjil"
- ✅ Menambahkan keterangan "Semester otomatis diatur ke Ganjil"

### 4. **Views - Edit Form**
**File:** `resources/views/admin/tahun-akademik/edit.blade.php`
- ✅ Mengganti dropdown semester dengan input readonly "Ganjil"
- ✅ Menambahkan keterangan "Semester otomatis diatur ke Ganjil"

### 5. **Views - Index Page**
**File:** `resources/views/admin/tahun-akademik/index.blade.php`
- ✅ Menghapus filter semester dari form pencarian
- ✅ Menghapus kolom semester dari tabel
- ✅ Menambahkan "Semester Ganjil" sebagai subtitle di kolom tahun
- ✅ Mengubah placeholder pencarian dari "Cari tahun atau semester" ke "Cari tahun akademik"
- ✅ Menghapus 'semester' dari sorting options
- ✅ Menghapus referensi semester dari konfirmasi delete
- ✅ Update JavaScript untuk menghapus parameter semester

### 6. **Views - Panitia Siswa**
**File:** `resources/views/panitia/siswa/index.blade.php`
- ✅ Menghapus tampilan semester dari dropdown tahun akademik

### 7. **Database Migration**
**File:** `database/migrations/2025_08_07_100000_update_semester_to_ganjil_only.php`
- ✅ Update semua record existing ke semester 'Ganjil'
- ✅ Set default value kolom semester ke 'Ganjil'

### 8. **Factory**
**File:** `database/factories/TahunAkademikFactory.php`
- ✅ Mengubah factory untuk selalu generate semester 'Ganjil'

## Perubahan UI/UX

### **Form Create & Edit:**
- **Sebelum:** Dropdown dengan pilihan "Ganjil" dan "Genap"
- **Sesudah:** Input readonly yang menampilkan "Ganjil" dengan keterangan

### **Index Page:**
- **Sebelum:** 
  - Filter semester dengan dropdown
  - Kolom semester terpisah di tabel
  - Sorting berdasarkan semester
- **Sesudah:**
  - Tidak ada filter semester
  - Semester ditampilkan sebagai subtitle "Semester Ganjil"
  - Sorting semester dihapus

### **Dropdown Tahun Akademik:**
- **Sebelum:** "2024/2025 - Ganjil"
- **Sesudah:** "2024/2025"

## Perubahan Logic

### **Controller Logic:**
```php
// Store & Update methods
$validated['semester'] = 'Ganjil'; // Otomatis set
```

### **Search Logic:**
```php
// Sebelum
$q->where('tahun', 'like', "%{$search}%")
  ->orWhere('semester', 'like', "%{$search}%");

// Sesudah  
$query->where('tahun', 'like', "%{$search}%");
```

## Database Changes

### **Migration Executed:**
- ✅ Semua record existing diupdate ke semester 'Ganjil'
- ✅ Default value kolom semester diset ke 'Ganjil'

### **Data Consistency:**
- ✅ Semua tahun akademik sekarang menggunakan semester 'Ganjil'
- ✅ Tidak ada data dengan semester 'Genap'

## Testing Results

### **Functionality Test:**
- ✅ Create tahun akademik baru → otomatis semester 'Ganjil'
- ✅ Edit tahun akademik existing → tetap semester 'Ganjil'
- ✅ Data existing sudah terupdate ke 'Ganjil'
- ✅ Form validation tidak memerlukan input semester

### **UI Test:**
- ✅ Form create menampilkan readonly "Ganjil"
- ✅ Form edit menampilkan readonly "Ganjil"
- ✅ Index page tidak ada filter semester
- ✅ Tabel menampilkan "Semester Ganjil" sebagai subtitle
- ✅ Delete confirmation tidak menyebut semester

## Views yang Tidak Berubah

Views berikut tetap menampilkan semester dari database (yang sekarang selalu 'Ganjil'):
- `resources/views/admin/tahun-akademik/show.blade.php`
- `resources/views/panitia/promethee/umum-form.blade.php`
- `resources/views/panitia/promethee/khusus-form.blade.php`
- `resources/views/panitia/reports/index.blade.php`

## Backward Compatibility

- ✅ Semua data existing tetap valid
- ✅ Tidak ada breaking changes pada API/model
- ✅ Views yang menampilkan semester tetap berfungsi
- ✅ Relasi database tetap utuh

## Summary

Sistem sekarang secara otomatis menggunakan semester "Ganjil" untuk semua tahun akademik:
- **User tidak perlu memilih semester** saat create/edit
- **Semester ditampilkan sebagai informasi** bukan input
- **Data consistency terjaga** dengan migration
- **UI lebih sederhana** tanpa pilihan yang tidak perlu

Perubahan ini menyederhanakan workflow pengelolaan tahun akademik karena sekolah hanya menggunakan semester ganjil.
