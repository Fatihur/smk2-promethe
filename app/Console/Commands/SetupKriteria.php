<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MasterKriteria;
use App\Models\KriteriaJurusan;
use App\Models\Jurusan;

class SetupKriteria extends Command
{
    protected $signature = 'setup:kriteria';
    protected $description = 'Setup master kriteria and kriteria jurusan data';

    public function handle()
    {
        $this->info('Setting up kriteria data...');

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

        $this->info('Creating master kriteria...');
        foreach ($masterKriteria as $kriteria) {
            $mk = MasterKriteria::updateOrCreate(
                ['kode_kriteria' => $kriteria['kode_kriteria']],
                $kriteria
            );
            $this->line("✓ {$mk->nama_kriteria}");
        }

        // Get all jurusan and master kriteria
        $jurusanList = Jurusan::where('is_active', true)->get();
        $masterKriteriaList = MasterKriteria::where('is_active', true)->get();

        $this->info('Creating kriteria jurusan mappings...');
        foreach ($jurusanList as $jurusan) {
            $this->line("Processing: {$jurusan->nama_jurusan}");
            
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

        $this->info('✓ Setup completed successfully!');
        $this->line('Master Kriteria: ' . MasterKriteria::count());
        $this->line('Kriteria Jurusan: ' . KriteriaJurusan::count());
    }
}
