<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Setup database connection
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'smk2_promethe',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    // Create master kriteria
    $masterKriteria = [
        [
            'kode_kriteria' => 'TPA',
            'nama_kriteria' => 'Tes Potensi Akademik (TPA)',
            'deskripsi' => 'Penilaian kemampuan akademik dasar siswa meliputi logika, matematika, dan bahasa',
            'tipe' => 'benefit',
            'is_active' => true,
        ],
        [
            'kode_kriteria' => 'PSI',
            'nama_kriteria' => 'Tes Psikologi',
            'deskripsi' => 'Penilaian aspek psikologis dan kepribadian siswa',
            'tipe' => 'benefit',
            'is_active' => true,
        ],
        [
            'kode_kriteria' => 'MNT',
            'nama_kriteria' => 'Minat dan Bakat',
            'deskripsi' => 'Penilaian minat dan bakat siswa terhadap bidang keahlian',
            'tipe' => 'benefit',
            'is_active' => true,
        ],
        [
            'kode_kriteria' => 'TKD',
            'nama_kriteria' => 'Kemampuan Teknik Dasar',
            'deskripsi' => 'Penilaian kemampuan teknik dasar dan pemahaman praktis',
            'tipe' => 'benefit',
            'is_active' => true,
        ],
        [
            'kode_kriteria' => 'MTK',
            'nama_kriteria' => 'Nilai Matematika',
            'deskripsi' => 'Nilai mata pelajaran matematika dari rapor',
            'tipe' => 'benefit',
            'is_active' => true,
        ],
        [
            'kode_kriteria' => 'IND',
            'nama_kriteria' => 'Nilai Bahasa Indonesia',
            'deskripsi' => 'Nilai mata pelajaran bahasa Indonesia dari rapor',
            'tipe' => 'benefit',
            'is_active' => true,
        ],
        [
            'kode_kriteria' => 'IPA',
            'nama_kriteria' => 'Nilai IPA',
            'deskripsi' => 'Nilai mata pelajaran IPA dari rapor',
            'tipe' => 'benefit',
            'is_active' => true,
        ],
    ];

    echo "Creating master kriteria...\n";
    foreach ($masterKriteria as $kriteria) {
        $existing = Capsule::table('master_kriteria')
            ->where('kode_kriteria', $kriteria['kode_kriteria'])
            ->first();
        
        if (!$existing) {
            Capsule::table('master_kriteria')->insert(array_merge($kriteria, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
            echo "Created: {$kriteria['nama_kriteria']}\n";
        } else {
            echo "Exists: {$kriteria['nama_kriteria']}\n";
        }
    }

    // Get all jurusan
    $jurusanList = Capsule::table('jurusan')->get();
    $masterKriteriaList = Capsule::table('master_kriteria')->where('is_active', true)->get();

    echo "\nCreating kriteria jurusan mappings...\n";
    foreach ($jurusanList as $jurusan) {
        echo "Processing jurusan: {$jurusan->nama_jurusan}\n";
        
        foreach ($masterKriteriaList as $mk) {
            $existing = Capsule::table('kriteria_jurusan')
                ->where('master_kriteria_id', $mk->id)
                ->where('jurusan_id', $jurusan->id)
                ->first();
            
            if (!$existing) {
                Capsule::table('kriteria_jurusan')->insert([
                    'master_kriteria_id' => $mk->id,
                    'jurusan_id' => $jurusan->id,
                    'nilai_min' => 0,
                    'nilai_max' => 100,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                echo "  - Added kriteria: {$mk->nama_kriteria}\n";
            }
        }
    }

    echo "\nSetup completed successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

function now() {
    return date('Y-m-d H:i:s');
}
