<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MasterKriteria;
use App\Models\KriteriaJurusan;
use App\Models\Jurusan;
use App\Models\Siswa;
use App\Models\TahunAkademik;

class SetupTestData extends Command
{
    protected $signature = 'setup:test-data';
    protected $description = 'Setup test data for kriteria and siswa';

    public function handle()
    {
        $this->info('Setting up test data...');

        // Setup kriteria
        $this->setupKriteria();
        
        // Setup siswa
        $this->setupSiswa();

        $this->info('✓ Test data setup completed!');
        $this->displayStats();
    }

    private function setupKriteria()
    {
        $this->info('Creating master kriteria...');
        
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
    }

    private function setupSiswa()
    {
        $this->info('Creating test siswa...');
        
        $tahunAktif = TahunAkademik::where('is_active', true)->first();
        $jurusanList = Jurusan::where('is_active', true)->get();

        if (!$tahunAktif || $jurusanList->isEmpty()) {
            $this->error('Tidak ada tahun akademik aktif atau jurusan aktif.');
            return;
        }

        $siswaData = [
            [
                'no_pendaftaran' => '20240001',
                'nisn' => '3451056465',
                'nama_lengkap' => 'Sit Nam sunt nisi ut',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2008-05-15',
                'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                'no_telepon' => '081234567890',
                'email' => 'siswa1@example.com',
                'nama_ayah' => 'Budi Santoso',
                'nama_ibu' => 'Siti Rahayu',
                'pekerjaan_ayah' => 'Pegawai Swasta',
                'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                'penghasilan_ortu' => 5000000,
                'asal_sekolah' => 'SMP Negeri 1 Jakarta',
                'kategori' => 'umum',
                'status' => 'diterima',
            ],
            [
                'no_pendaftaran' => '20240002',
                'nisn' => '3451056466',
                'nama_lengkap' => 'Ahmad Rizki Pratama',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2008-03-20',
                'alamat' => 'Jl. Sudirman No. 456, Bandung',
                'no_telepon' => '081234567891',
                'email' => 'siswa2@example.com',
                'nama_ayah' => 'Rizki Wijaya',
                'nama_ibu' => 'Dewi Sartika',
                'pekerjaan_ayah' => 'Guru',
                'pekerjaan_ibu' => 'Perawat',
                'penghasilan_ortu' => 7000000,
                'asal_sekolah' => 'SMP Negeri 2 Bandung',
                'kategori' => 'umum',
                'status' => 'diterima',
            ],
            [
                'no_pendaftaran' => '20240003',
                'nisn' => '3451056467',
                'nama_lengkap' => 'Sari Indah Permata',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '2008-07-10',
                'alamat' => 'Jl. Pahlawan No. 789, Surabaya',
                'no_telepon' => '081234567892',
                'email' => 'siswa3@example.com',
                'nama_ayah' => 'Indra Permana',
                'nama_ibu' => 'Sari Wulandari',
                'pekerjaan_ayah' => 'Dokter',
                'pekerjaan_ibu' => 'Bidan',
                'penghasilan_ortu' => 12000000,
                'asal_sekolah' => 'SMP Negeri 3 Surabaya',
                'kategori' => 'khusus',
                'status' => 'diterima',
            ],
            [
                'no_pendaftaran' => '20240004',
                'nisn' => '3451056468',
                'nama_lengkap' => 'Dedi Kurniawan',
                'jenis_kelamin' => 'L',
                'tempat_lahir' => 'Medan',
                'tanggal_lahir' => '2008-01-25',
                'alamat' => 'Jl. Gatot Subroto No. 321, Medan',
                'no_telepon' => '081234567893',
                'email' => 'siswa4@example.com',
                'nama_ayah' => 'Kurnia Setiawan',
                'nama_ibu' => 'Rina Marlina',
                'pekerjaan_ayah' => 'Wiraswasta',
                'pekerjaan_ibu' => 'Guru',
                'penghasilan_ortu' => 8000000,
                'asal_sekolah' => 'SMP Negeri 4 Medan',
                'kategori' => 'umum',
                'status' => 'diterima',
            ],
            [
                'no_pendaftaran' => '20240005',
                'nisn' => '3451056469',
                'nama_lengkap' => 'Maya Sari Dewi',
                'jenis_kelamin' => 'P',
                'tempat_lahir' => 'Yogyakarta',
                'tanggal_lahir' => '2008-09-12',
                'alamat' => 'Jl. Malioboro No. 654, Yogyakarta',
                'no_telepon' => '081234567894',
                'email' => 'siswa5@example.com',
                'nama_ayah' => 'Sari Gunawan',
                'nama_ibu' => 'Maya Kusuma',
                'pekerjaan_ayah' => 'PNS',
                'pekerjaan_ibu' => 'Dosen',
                'penghasilan_ortu' => 9000000,
                'asal_sekolah' => 'SMP Negeri 5 Yogyakarta',
                'kategori' => 'umum',
                'status' => 'diterima',
            ],
        ];

        foreach ($siswaData as $index => $data) {
            // Assign to different jurusan cyclically
            $jurusan = $jurusanList[$index % $jurusanList->count()];
            
            $data['tahun_akademik_id'] = $tahunAktif->id;
            $data['pilihan_jurusan_1'] = $jurusan->id;
            $data['pilihan_jurusan_2'] = $jurusanList[($index + 1) % $jurusanList->count()]->id;

            $siswa = Siswa::updateOrCreate(
                ['no_pendaftaran' => $data['no_pendaftaran']],
                $data
            );
            
            $this->line("✓ {$siswa->nama_lengkap} -> {$jurusan->nama_jurusan}");
        }
    }

    private function displayStats()
    {
        $this->line('');
        $this->line('=== STATISTICS ===');
        $this->line('Master Kriteria: ' . MasterKriteria::count());
        $this->line('Kriteria Jurusan: ' . KriteriaJurusan::count());
        $this->line('Jurusan: ' . Jurusan::count());
        $this->line('Siswa: ' . Siswa::count());
    }
}
