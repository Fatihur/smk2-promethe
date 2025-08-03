<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterKriteria;
use App\Models\KriteriaJurusan;
use App\Models\Jurusan;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Create master kriteria if not exists
        $masterKriteria = [
            [
                'kode_kriteria' => 'K001',
                'nama_kriteria' => 'Nilai Matematika',
                'deskripsi' => 'Nilai mata pelajaran matematika',
                'tipe' => 'benefit',
                'is_active' => true,
            ],
            [
                'kode_kriteria' => 'K002',
                'nama_kriteria' => 'Nilai Bahasa Indonesia',
                'deskripsi' => 'Nilai mata pelajaran bahasa Indonesia',
                'tipe' => 'benefit',
                'is_active' => true,
            ],
            [
                'kode_kriteria' => 'K003',
                'nama_kriteria' => 'Nilai IPA',
                'deskripsi' => 'Nilai mata pelajaran IPA',
                'tipe' => 'benefit',
                'is_active' => true,
            ],
        ];

        foreach ($masterKriteria as $kriteria) {
            MasterKriteria::updateOrCreate(
                ['kode_kriteria' => $kriteria['kode_kriteria']],
                $kriteria
            );
        }

        // Get first jurusan
        $jurusan = Jurusan::first();
        if ($jurusan) {
            // Create kriteria jurusan for each master kriteria
            $masterKriteriaList = MasterKriteria::all();
            foreach ($masterKriteriaList as $mk) {
                KriteriaJurusan::updateOrCreate(
                    [
                        'master_kriteria_id' => $mk->id,
                        'jurusan_id' => $jurusan->id,
                    ],
                    [
                        'nilai_min' => 0,
                        'nilai_max' => 100,
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('Test data created successfully!');
    }
}
