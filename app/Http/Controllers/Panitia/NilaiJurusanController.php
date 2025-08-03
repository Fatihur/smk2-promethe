<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Siswa;
use App\Models\KriteriaJurusan;
use App\Models\MasterKriteria;
use App\Models\NilaiSiswa;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class NilaiJurusanController extends Controller
{
    /**
     * Display list of jurusan for nilai input
     */
    public function index()
    {
        $tahunAktif = TahunAkademik::where('is_active', true)->first();
        
        $jurusan = Jurusan::withCount([
            'siswaPilihan1 as total_siswa' => function($query) use ($tahunAktif) {
                if ($tahunAktif) {
                    $query->where('tahun_akademik_id', $tahunAktif->id);
                }
            },
            'siswaPilihan1 as siswa_sudah_dinilai' => function($query) use ($tahunAktif) {
                if ($tahunAktif) {
                    $query->where('tahun_akademik_id', $tahunAktif->id)
                          ->whereHas('nilaiSiswa');
                }
            }
        ])->where('is_active', true)->get();

        return view('panitia.nilai-jurusan.index', compact('jurusan', 'tahunAktif'));
    }

    /**
     * Display siswa list for specific jurusan
     */
    public function siswa(Jurusan $jurusan)
    {
        $tahunAktif = TahunAkademik::where('is_active', true)->first();
        
        if (!$tahunAktif) {
            return redirect()->back()->with('error', 'Tidak ada tahun akademik yang aktif.');
        }

        // Get kriteria for this jurusan
        $kriteriaJurusan = KriteriaJurusan::where('jurusan_id', $jurusan->id)
            ->where('is_active', true)
            ->with('masterKriteria')
            ->get();

        if ($kriteriaJurusan->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada kriteria yang aktif untuk jurusan ini.');
        }

        // Get siswa for this jurusan
        $siswa = Siswa::where('pilihan_jurusan_1', $jurusan->id)
            ->where('tahun_akademik_id', $tahunAktif->id)
            ->with(['nilaiSiswa.masterKriteria'])
            ->orderBy('nama_lengkap')
            ->get();

        // Calculate progress for each siswa
        $totalKriteria = $kriteriaJurusan->count();
        foreach ($siswa as $s) {
            $nilaiCount = $s->nilaiSiswa->whereIn('master_kriteria_id', $kriteriaJurusan->pluck('master_kriteria_id'))->count();
            $s->progress_persen = $totalKriteria > 0 ? round(($nilaiCount / $totalKriteria) * 100) : 0;
            $s->is_complete = $nilaiCount >= $totalKriteria;
        }

        return view('panitia.nilai-jurusan.siswa', compact('jurusan', 'siswa', 'kriteriaJurusan', 'tahunAktif'));
    }

    /**
     * Show form for input nilai for specific siswa in jurusan
     */
    public function inputNilai(Jurusan $jurusan, Siswa $siswa)
    {
        // Validate siswa belongs to this jurusan
        if ($siswa->pilihan_jurusan_1 != $jurusan->id) {
            return redirect()->back()->with('error', 'Siswa tidak terdaftar di jurusan ini.');
        }

        // Get kriteria for this jurusan
        $kriteriaJurusan = KriteriaJurusan::where('jurusan_id', $jurusan->id)
            ->where('is_active', true)
            ->with('masterKriteria')
            ->get();

        // If no kriteria found, create default ones
        if ($kriteriaJurusan->isEmpty()) {
            $this->createDefaultKriteria($jurusan->id);
            $kriteriaJurusan = KriteriaJurusan::where('jurusan_id', $jurusan->id)
                ->where('is_active', true)
                ->with('masterKriteria')
                ->get();
        }

        // Get existing nilai for this siswa
        $nilaiSiswa = [];
        $existingNilai = $siswa->nilaiSiswa()->with('masterKriteria')->get();
        foreach($existingNilai as $nilai) {
            $nilaiSiswa[$nilai->master_kriteria_id] = $nilai;
        }

        return view('panitia.nilai-jurusan.input', compact('jurusan', 'siswa', 'kriteriaJurusan', 'nilaiSiswa'));
    }

    /**
     * Store nilai for siswa
     */
    public function storeNilai(Request $request, Jurusan $jurusan, Siswa $siswa)
    {
        // Validate siswa belongs to this jurusan
        if ($siswa->pilihan_jurusan_1 != $jurusan->id) {
            return redirect()->back()->with('error', 'Siswa tidak terdaftar di jurusan ini.');
        }

        // Get kriteria for validation
        $kriteriaJurusan = KriteriaJurusan::where('jurusan_id', $jurusan->id)
            ->where('is_active', true)
            ->with('masterKriteria')
            ->get();

        // Build validation rules dynamically based on kriteria ranges
        $rules = ['nilai' => 'required|array'];
        foreach ($kriteriaJurusan as $kj) {
            $rules["nilai.{$kj->master_kriteria_id}"] = "required|numeric|min:{$kj->nilai_min}|max:{$kj->nilai_max}";
        }

        $request->validate($rules);

        // Store nilai
        foreach ($request->nilai as $masterKriteriaId => $nilaiValue) {
            NilaiSiswa::updateOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'master_kriteria_id' => $masterKriteriaId,
                ],
                [
                    'nilai' => $nilaiValue,
                    'keterangan' => $request->keterangan[$masterKriteriaId] ?? null,
                ]
            );
        }

        return redirect()->route('panitia.nilai-jurusan.siswa', $jurusan)
            ->with('success', "Nilai untuk {$siswa->nama_lengkap} berhasil disimpan.");
    }

    /**
     * Bulk input nilai for multiple siswa
     */
    public function bulkInput(Jurusan $jurusan)
    {
        $tahunAktif = TahunAkademik::where('is_active', true)->first();
        
        if (!$tahunAktif) {
            return redirect()->back()->with('error', 'Tidak ada tahun akademik yang aktif.');
        }

        // Get kriteria for this jurusan
        $kriteriaJurusan = KriteriaJurusan::where('jurusan_id', $jurusan->id)
            ->where('is_active', true)
            ->with('masterKriteria')
            ->get();

        // If no kriteria found, create default ones
        if ($kriteriaJurusan->isEmpty()) {
            $this->createDefaultKriteria($jurusan->id);
            $kriteriaJurusan = KriteriaJurusan::where('jurusan_id', $jurusan->id)
                ->where('is_active', true)
                ->with('masterKriteria')
                ->get();
        }

        // Get siswa for this jurusan
        $siswa = Siswa::where('pilihan_jurusan_1', $jurusan->id)
            ->where('tahun_akademik_id', $tahunAktif->id)
            ->with(['nilaiSiswa.masterKriteria'])
            ->orderBy('nama_lengkap')
            ->get();

        // Prepare existing nilai data
        $nilaiData = [];
        foreach ($siswa as $s) {
            foreach ($s->nilaiSiswa as $nilai) {
                $nilaiData[$s->id][$nilai->master_kriteria_id] = $nilai;
            }
        }

        return view('panitia.nilai-jurusan.bulk', compact('jurusan', 'siswa', 'kriteriaJurusan', 'nilaiData'));
    }

    /**
     * Store bulk nilai
     */
    public function storeBulkNilai(Request $request, Jurusan $jurusan)
    {
        $tahunAktif = TahunAkademik::where('is_active', true)->first();

        if (!$tahunAktif) {
            return redirect()->back()->with('error', 'Tidak ada tahun akademik yang aktif.');
        }

        // Get kriteria for validation
        $kriteriaJurusan = KriteriaJurusan::where('jurusan_id', $jurusan->id)
            ->where('is_active', true)
            ->get();

        if ($kriteriaJurusan->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada kriteria yang aktif untuk jurusan ini.');
        }

        // Validate that we have nilai data
        if (!$request->has('nilai') || !is_array($request->nilai)) {
            return redirect()->back()->with('error', 'Data nilai tidak valid.');
        }

        // Build validation rules dynamically
        $rules = ['nilai' => 'required|array'];
        $messages = [];

        foreach ($request->nilai as $siswaId => $nilaiSiswa) {
            if (!is_array($nilaiSiswa)) continue;

            foreach ($kriteriaJurusan as $kj) {
                $fieldName = "nilai.{$siswaId}.{$kj->master_kriteria_id}";
                if (isset($nilaiSiswa[$kj->master_kriteria_id]) && !empty($nilaiSiswa[$kj->master_kriteria_id])) {
                    $rules[$fieldName] = "numeric|min:{$kj->nilai_min}|max:{$kj->nilai_max}";
                    $messages["{$fieldName}.numeric"] = "Nilai harus berupa angka.";
                    $messages["{$fieldName}.min"] = "Nilai minimal {$kj->nilai_min}.";
                    $messages["{$fieldName}.max"] = "Nilai maksimal {$kj->nilai_max}.";
                }
            }
        }

        try {
            $request->validate($rules, $messages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Terdapat kesalahan validasi pada input nilai.');
        }

        $updatedCount = 0;
        $errorCount = 0;
        $errors = [];

        try {
            DB::beginTransaction();

            // Store nilai for each siswa
            foreach ($request->nilai as $siswaId => $nilaiSiswa) {
                if (!is_array($nilaiSiswa)) continue;

                $siswa = Siswa::find($siswaId);
                if (!$siswa || $siswa->pilihan_jurusan_1 != $jurusan->id) {
                    $errors[] = "Siswa dengan ID {$siswaId} tidak valid untuk jurusan ini.";
                    $errorCount++;
                    continue;
                }

                foreach ($nilaiSiswa as $masterKriteriaId => $nilaiValue) {
                    if (empty($nilaiValue) || !is_numeric($nilaiValue)) {
                        continue; // Skip empty or non-numeric values
                    }

                    try {
                        // Use updateOrCreate to handle duplicates gracefully
                        NilaiSiswa::updateOrCreate(
                            [
                                'siswa_id' => $siswaId,
                                'master_kriteria_id' => $masterKriteriaId,
                            ],
                            [
                                'nilai' => (float) $nilaiValue,
                                'updated_at' => now(),
                            ]
                        );
                        $updatedCount++;
                    } catch (\Exception $e) {
                        $errors[] = "Gagal menyimpan nilai untuk siswa {$siswa->nama_lengkap}: " . $e->getMessage();
                        $errorCount++;
                        Log::error("Error saving nilai for siswa {$siswaId}, kriteria {$masterKriteriaId}: " . $e->getMessage());
                    }
                }
            }

            DB::commit();

            $message = "Berhasil menyimpan {$updatedCount} nilai siswa.";
            if ($errorCount > 0) {
                $message .= " {$errorCount} nilai gagal disimpan.";
            }

            $alertType = $errorCount > 0 ? 'warning' : 'success';

            return redirect()->back()
                ->with($alertType, $message)
                ->with('errors', $errors);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error storing bulk nilai: ' . $e->getMessage(), [
                'jurusan_id' => $jurusan->id,
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan sistem saat menyimpan nilai. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Setup test data for development
     */
    public function setupData()
    {
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

            $createdKriteria = [];
            foreach ($masterKriteria as $kriteria) {
                $mk = MasterKriteria::updateOrCreate(
                    ['kode_kriteria' => $kriteria['kode_kriteria']],
                    $kriteria
                );
                $createdKriteria[] = $mk->nama_kriteria;
            }

            // Get all jurusan and create mappings
            $jurusanList = Jurusan::where('is_active', true)->get();
            $masterKriteriaList = MasterKriteria::where('is_active', true)->get();

            $mappingCount = 0;
            foreach ($jurusanList as $jurusan) {
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
                    $mappingCount++;
                }
            }

            // Create test siswa
            $tahunAktif = TahunAkademik::where('is_active', true)->first();
            if ($tahunAktif && !$jurusanList->isEmpty()) {
                $siswaData = [
                    [
                        'no_pendaftaran' => '20240001',
                        'nisn' => '3451056465',
                        'nama_lengkap' => 'Sit Nam sunt nisi ut',
                        'jenis_kelamin' => 'L',
                        'tempat_lahir' => 'Jakarta',
                        'tanggal_lahir' => '2008-05-15',
                        'alamat' => 'Jl. Merdeka No. 123, Jakarta',
                        'email' => 'siswa1@example.com',
                        'nama_ayah' => 'Budi Santoso',
                        'asal_sekolah' => 'SMP Negeri 1 Jakarta',
                        'kategori' => 'umum',
                        'status' => 'diterima',
                        'tahun_akademik_id' => $tahunAktif->id,
                        'pilihan_jurusan_1' => $jurusanList->first()->id,
                        'pilihan_jurusan_2' => $jurusanList->count() > 1 ? $jurusanList->skip(1)->first()->id : $jurusanList->first()->id,
                    ],
                    [
                        'no_pendaftaran' => '20240002',
                        'nisn' => '3451056466',
                        'nama_lengkap' => 'Ahmad Rizki Pratama',
                        'jenis_kelamin' => 'L',
                        'tempat_lahir' => 'Bandung',
                        'tanggal_lahir' => '2008-03-20',
                        'alamat' => 'Jl. Sudirman No. 456, Bandung',
                        'email' => 'siswa2@example.com',
                        'nama_ayah' => 'Rizki Wijaya',
                        'asal_sekolah' => 'SMP Negeri 2 Bandung',
                        'kategori' => 'umum',
                        'status' => 'diterima',
                        'tahun_akademik_id' => $tahunAktif->id,
                        'pilihan_jurusan_1' => $jurusanList->first()->id,
                        'pilihan_jurusan_2' => $jurusanList->count() > 1 ? $jurusanList->skip(1)->first()->id : $jurusanList->first()->id,
                    ],
                ];

                $createdSiswa = [];
                foreach ($siswaData as $data) {
                    $siswa = Siswa::updateOrCreate(
                        ['no_pendaftaran' => $data['no_pendaftaran']],
                        $data
                    );
                    $createdSiswa[] = $siswa->nama_lengkap;
                }
            }

            return redirect()->route('panitia.nilai-jurusan.index')->with('success',
                'Data berhasil dibuat! ' .
                'Master Kriteria: ' . count($createdKriteria) . ', ' .
                'Mapping: ' . $mappingCount . ', ' .
                'Siswa: ' . (isset($createdSiswa) ? count($createdSiswa) : 0)
            );

        } catch (\Exception $e) {
            Log::error('Setup data error: ' . $e->getMessage());
            return redirect()->route('panitia.nilai-jurusan.index')->with('error', 'Gagal membuat data: ' . $e->getMessage());
        }
    }

    /**
     * Clear all nilai siswa data for testing
     */
    public function clearNilai()
    {
        try {
            $count = NilaiSiswa::count();
            NilaiSiswa::truncate();

            return redirect()->route('panitia.nilai-jurusan.index')->with('success',
                'Berhasil menghapus ' . $count . ' data nilai siswa'
            );
        } catch (\Exception $e) {
            Log::error('Clear nilai error: ' . $e->getMessage());
            return redirect()->route('panitia.nilai-jurusan.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Create default kriteria for jurusan if none exist
     */
    private function createDefaultKriteria($jurusanId)
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
            $mk = MasterKriteria::updateOrCreate(
                ['kode_kriteria' => $kriteria['kode_kriteria']],
                $kriteria
            );

            // Create kriteria jurusan mapping
            KriteriaJurusan::updateOrCreate(
                [
                    'master_kriteria_id' => $mk->id,
                    'jurusan_id' => $jurusanId,
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
