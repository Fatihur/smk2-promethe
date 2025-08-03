<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterKriteria;
use App\Models\KriteriaJurusan;
use App\Models\Jurusan;

class KriteriaJurusanSeeder extends Seeder
{
    public function run()
    {
        // Create master kriteria if not exists
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

        foreach ($masterKriteria as $kriteria) {
            MasterKriteria::updateOrCreate(
                ['kode_kriteria' => $kriteria['kode_kriteria']],
                $kriteria
            );
        }

        // Get all jurusan
        $jurusanList = Jurusan::all();
        $masterKriteriaList = MasterKriteria::where('is_active', true)->get();

        foreach ($jurusanList as $jurusan) {
            // Define kriteria for each jurusan with specific ranges
            $kriteriaConfig = $this->getKriteriaConfigForJurusan($jurusan->kode_jurusan);
            
            foreach ($masterKriteriaList as $mk) {
                // Check if this kriteria should be used for this jurusan
                if (isset($kriteriaConfig[$mk->kode_kriteria])) {
                    $config = $kriteriaConfig[$mk->kode_kriteria];
                    
                    KriteriaJurusan::updateOrCreate(
                        [
                            'master_kriteria_id' => $mk->id,
                            'jurusan_id' => $jurusan->id,
                        ],
                        [
                            'nilai_min' => $config['nilai_min'],
                            'nilai_max' => $config['nilai_max'],
                            'is_active' => true,
                        ]
                    );
                }
            }
        }

        $this->command->info('Kriteria jurusan seeded successfully!');
    }

    /**
     * Get kriteria configuration for specific jurusan
     */
    private function getKriteriaConfigForJurusan($kodeJurusan)
    {
        // Common kriteria for all jurusan
        $commonKriteria = [
            'TPA' => ['nilai_min' => 0, 'nilai_max' => 100],
            'PSI' => ['nilai_min' => 0, 'nilai_max' => 100],
            'MNT' => ['nilai_min' => 0, 'nilai_max' => 100],
            'MTK' => ['nilai_min' => 0, 'nilai_max' => 100],
            'IND' => ['nilai_min' => 0, 'nilai_max' => 100],
            'IPA' => ['nilai_min' => 0, 'nilai_max' => 100],
        ];

        // Specific kriteria based on jurusan
        switch ($kodeJurusan) {
            case 'TKJ':
            case 'RPL':
            case 'MM':
                // IT-related jurusan
                return array_merge($commonKriteria, [
                    'TKD' => ['nilai_min' => 0, 'nilai_max' => 100], // Kemampuan Teknik Dasar
                ]);

            case 'TAB':
            case 'TITL':
            case 'TPM':
            case 'TKR':
                // Engineering jurusan
                return array_merge($commonKriteria, [
                    'TKD' => ['nilai_min' => 0, 'nilai_max' => 100], // Kemampuan Teknik Dasar
                ]);

            case 'AKL':
            case 'OTKP':
            case 'BDP':
                // Business jurusan
                return $commonKriteria; // Only common kriteria

            default:
                return $commonKriteria;
        }
    }
}
