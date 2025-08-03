<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert master kriteria
        $masterKriteria = [
            [
                'kode_kriteria' => 'TPA',
                'nama_kriteria' => 'Tes Potensi Akademik (TPA)',
                'deskripsi' => 'Penilaian kemampuan akademik dasar siswa meliputi logika, matematika, dan bahasa',
                'tipe' => 'benefit',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_kriteria' => 'PSI',
                'nama_kriteria' => 'Tes Psikologi',
                'deskripsi' => 'Penilaian aspek psikologis dan kepribadian siswa',
                'tipe' => 'benefit',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_kriteria' => 'MNT',
                'nama_kriteria' => 'Minat dan Bakat',
                'deskripsi' => 'Penilaian minat dan bakat siswa terhadap bidang keahlian',
                'tipe' => 'benefit',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_kriteria' => 'TKD',
                'nama_kriteria' => 'Kemampuan Teknik Dasar',
                'deskripsi' => 'Penilaian kemampuan teknik dasar dan pemahaman praktis',
                'tipe' => 'benefit',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_kriteria' => 'MTK',
                'nama_kriteria' => 'Nilai Matematika',
                'deskripsi' => 'Nilai mata pelajaran matematika dari rapor',
                'tipe' => 'benefit',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_kriteria' => 'IND',
                'nama_kriteria' => 'Nilai Bahasa Indonesia',
                'deskripsi' => 'Nilai mata pelajaran bahasa Indonesia dari rapor',
                'tipe' => 'benefit',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_kriteria' => 'IPA',
                'nama_kriteria' => 'Nilai IPA',
                'deskripsi' => 'Nilai mata pelajaran IPA dari rapor',
                'tipe' => 'benefit',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($masterKriteria as $kriteria) {
            DB::table('master_kriteria')->insertOrIgnore($kriteria);
        }

        // Get all jurusan and master kriteria IDs
        $jurusanIds = DB::table('jurusan')->where('is_active', true)->pluck('id');
        $masterKriteriaIds = DB::table('master_kriteria')->where('is_active', true)->pluck('id');

        // Insert kriteria jurusan for all combinations
        foreach ($jurusanIds as $jurusanId) {
            foreach ($masterKriteriaIds as $masterKriteriaId) {
                DB::table('kriteria_jurusan')->insertOrIgnore([
                    'master_kriteria_id' => $masterKriteriaId,
                    'jurusan_id' => $jurusanId,
                    'nilai_min' => 0,
                    'nilai_max' => 100,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove kriteria jurusan
        DB::table('kriteria_jurusan')->truncate();
        
        // Remove master kriteria
        DB::table('master_kriteria')->whereIn('kode_kriteria', [
            'TPA', 'PSI', 'MNT', 'TKD', 'MTK', 'IND', 'IPA'
        ])->delete();
    }
};
