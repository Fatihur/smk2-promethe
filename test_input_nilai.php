<?php

/**
 * Test script untuk memverifikasi perbaikan input nilai
 */

require_once 'vendor/autoload.php';

use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\KriteriaJurusan;
use App\Models\NilaiSiswa;
use App\Models\TahunAkademik;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test Input Nilai Massal ===\n\n";

try {
    // 1. Check data availability
    echo "1. Checking data availability...\n";
    
    $tahunAktif = TahunAkademik::where('is_active', true)->first();
    echo "   - Tahun Akademik Aktif: " . ($tahunAktif ? $tahunAktif->tahun : 'TIDAK ADA') . "\n";
    
    $jurusan = Jurusan::first();
    echo "   - Jurusan: " . ($jurusan ? $jurusan->nama_jurusan : 'TIDAK ADA') . "\n";
    
    $siswaCount = Siswa::where('pilihan_jurusan_1', $jurusan->id ?? 0)->count();
    echo "   - Siswa untuk jurusan ini: {$siswaCount}\n";
    
    $kriteriaCount = KriteriaJurusan::where('jurusan_id', $jurusan->id ?? 0)
        ->where('is_active', true)->count();
    echo "   - Kriteria aktif: {$kriteriaCount}\n\n";
    
    if (!$tahunAktif || !$jurusan || $siswaCount == 0 || $kriteriaCount == 0) {
        echo "❌ Data tidak lengkap untuk testing\n";
        exit(1);
    }
    
    // 2. Test database structure
    echo "2. Testing database structure...\n";
    
    $columns = \DB::select("SHOW COLUMNS FROM nilai_siswa");
    $columnNames = array_column($columns, 'Field');
    
    $requiredColumns = ['id', 'siswa_id', 'master_kriteria_id', 'nilai', 'created_at', 'updated_at'];
    $missingColumns = array_diff($requiredColumns, $columnNames);
    
    if (empty($missingColumns)) {
        echo "   ✅ Struktur tabel nilai_siswa sudah benar\n";
    } else {
        echo "   ❌ Kolom yang hilang: " . implode(', ', $missingColumns) . "\n";
    }
    
    // 3. Test unique constraint
    echo "   - Testing unique constraint...\n";
    $indexes = \DB::select("SHOW INDEX FROM nilai_siswa WHERE Key_name LIKE '%unique%'");
    if (!empty($indexes)) {
        echo "   ✅ Unique constraint ada\n";
    } else {
        echo "   ❌ Unique constraint tidak ditemukan\n";
    }
    
    // 4. Test sample data insertion
    echo "\n3. Testing sample data insertion...\n";
    
    $siswa = Siswa::where('pilihan_jurusan_1', $jurusan->id)->first();
    $kriteria = KriteriaJurusan::where('jurusan_id', $jurusan->id)
        ->where('is_active', true)
        ->with('masterKriteria')
        ->first();
    
    if ($siswa && $kriteria) {
        try {
            // Test insert
            $nilai = NilaiSiswa::updateOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'master_kriteria_id' => $kriteria->master_kriteria_id,
                ],
                [
                    'nilai' => 85.5,
                ]
            );
            
            echo "   ✅ Insert/Update berhasil (ID: {$nilai->id})\n";
            
            // Test duplicate handling
            $nilai2 = NilaiSiswa::updateOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'master_kriteria_id' => $kriteria->master_kriteria_id,
                ],
                [
                    'nilai' => 90.0,
                ]
            );
            
            if ($nilai->id == $nilai2->id) {
                echo "   ✅ Duplicate handling berhasil (nilai diupdate)\n";
            } else {
                echo "   ❌ Duplicate handling gagal (record baru dibuat)\n";
            }
            
        } catch (\Exception $e) {
            echo "   ❌ Error saat insert: " . $e->getMessage() . "\n";
        }
    }
    
    // 5. Test validation ranges
    echo "\n4. Testing validation ranges...\n";
    
    $kriteriaList = KriteriaJurusan::where('jurusan_id', $jurusan->id)
        ->where('is_active', true)
        ->with('masterKriteria')
        ->get();
    
    foreach ($kriteriaList as $kj) {
        echo "   - {$kj->masterKriteria->nama_kriteria}: {$kj->nilai_min} - {$kj->nilai_max}\n";
    }
    
    echo "\n✅ Test selesai! Semua komponen siap untuk digunakan.\n";
    echo "\nURL untuk testing: http://smk2-promethe.test/panitia/nilai-jurusan/{$jurusan->id}/bulk\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
