<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Siswa;
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
     * Clear all nilai siswa data
     */
    public function clearNilai()
    {
        try {
            DB::beginTransaction();

            // Delete all nilai siswa records
            $deletedCount = NilaiSiswa::count();
            NilaiSiswa::truncate();

            DB::commit();

            return redirect()->route('panitia.nilai-jurusan.index')
                ->with('success', "Berhasil menghapus {$deletedCount} data nilai siswa.");

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error clearing nilai siswa: ' . $e->getMessage());

            return redirect()->route('panitia.nilai-jurusan.index')
                ->with('error', 'Gagal menghapus data nilai siswa: ' . $e->getMessage());
        }
    }

    /**
     * Setup test data for nilai siswa
     */
    public function setupData()
    {
        try {
            DB::beginTransaction();

            $tahunAktif = TahunAkademik::where('is_active', true)->first();
            if (!$tahunAktif) {
                return redirect()->route('panitia.nilai-jurusan.index')
                    ->with('error', 'Tidak ada tahun akademik yang aktif.');
            }

            // Get active kriteria
            $kriteria = MasterKriteria::where('is_active', true)->get();
            if ($kriteria->isEmpty()) {
                return redirect()->route('panitia.nilai-jurusan.index')
                    ->with('error', 'Belum ada kriteria yang aktif dalam sistem.');
            }

            // Get sample siswa (first 10 from each category)
            $siswaKhusus = Siswa::where('kategori', 'khusus')
                ->where('tahun_akademik_id', $tahunAktif->id)
                ->take(10)
                ->get();

            $siswaUmum = Siswa::where('kategori', 'umum')
                ->where('tahun_akademik_id', $tahunAktif->id)
                ->take(10)
                ->get();

            $allSiswa = $siswaKhusus->merge($siswaUmum);
            $createdCount = 0;

            foreach ($allSiswa as $siswa) {
                foreach ($kriteria as $k) {
                    // Generate random nilai within range
                    $nilai = 0;

                    switch ($k->kode_kriteria) {
                        case 'TPA':
                            $nilai = rand(60, 95); // TPA score 60-95
                            break;
                        case 'PS':
                            $nilai = rand(70, 90); // Psikotes score 70-90
                            break;
                        case 'MB':
                            $nilai = rand(0, 1); // Minat Bakat 0 or 1
                            break;
                        default:
                            $nilai = rand((int)$k->nilai_min, (int)$k->nilai_max);
                    }

                    // Create or update nilai
                    NilaiSiswa::updateOrCreate(
                        [
                            'siswa_id' => $siswa->id,
                            'master_kriteria_id' => $k->id,
                        ],
                        [
                            'nilai' => $nilai,
                            'keterangan' => 'Data test otomatis'
                        ]
                    );

                    $createdCount++;
                }
            }

            DB::commit();

            return redirect()->route('panitia.nilai-jurusan.index')
                ->with('success', "Berhasil membuat {$createdCount} data nilai test untuk {$allSiswa->count()} siswa.");

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error setting up test data: ' . $e->getMessage());

            return redirect()->route('panitia.nilai-jurusan.index')
                ->with('error', 'Gagal membuat data test: ' . $e->getMessage());
        }
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

        // Get all active master kriteria (global criteria)
        $masterKriteria = MasterKriteria::where('is_active', true)
            ->orderBy('kode_kriteria')
            ->get();

        if ($masterKriteria->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada kriteria yang aktif dalam sistem.');
        }

        // Get siswa for this jurusan
        $siswa = Siswa::where('pilihan_jurusan_1', $jurusan->id)
            ->where('tahun_akademik_id', $tahunAktif->id)
            ->with(['nilaiSiswa.masterKriteria'])
            ->orderBy('nama_lengkap')
            ->get();

        // Calculate progress for each siswa
        $totalKriteria = $masterKriteria->count();
        foreach ($siswa as $s) {
            $nilaiCount = $s->nilaiSiswa->whereIn('master_kriteria_id', $masterKriteria->pluck('id'))->count();
            $s->progress_persen = $totalKriteria > 0 ? round(($nilaiCount / $totalKriteria) * 100) : 0;
            $s->is_complete = $nilaiCount >= $totalKriteria;
        }

        return view('panitia.nilai-jurusan.siswa', compact('jurusan', 'siswa', 'masterKriteria', 'tahunAktif'));
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

        // Get all active master kriteria (global criteria)
        $masterKriteria = MasterKriteria::where('is_active', true)
            ->orderBy('kode_kriteria')
            ->get();

        if ($masterKriteria->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada kriteria yang aktif dalam sistem.');
        }

        // Get existing nilai for this siswa
        $nilaiSiswa = [];
        $existingNilai = $siswa->nilaiSiswa()->with('masterKriteria')->get();
        foreach($existingNilai as $nilai) {
            $nilaiSiswa[$nilai->master_kriteria_id] = $nilai;
        }

        return view('panitia.nilai-jurusan.input', compact('jurusan', 'siswa', 'masterKriteria', 'nilaiSiswa'));
    }

    /**
     * Store nilai for specific siswa
     */
    public function storeNilai(Request $request, Jurusan $jurusan, Siswa $siswa)
    {
        // Validate siswa belongs to this jurusan
        if ($siswa->pilihan_jurusan_1 != $jurusan->id) {
            return redirect()->back()->with('error', 'Siswa tidak terdaftar di jurusan ini.');
        }

        // Get all active master kriteria for validation
        $masterKriteria = MasterKriteria::where('is_active', true)->get();

        // Validate input
        $rules = [];
        foreach ($masterKriteria as $kriteria) {
            $rules["nilai.{$kriteria->id}"] = [
                'required',
                'numeric',
                "min:{$kriteria->nilai_min}",
                "max:{$kriteria->nilai_max}"
            ];
        }

        $request->validate($rules, [
            'nilai.*.required' => 'Nilai kriteria harus diisi.',
            'nilai.*.numeric' => 'Nilai harus berupa angka.',
            'nilai.*.min' => 'Nilai tidak boleh kurang dari :min.',
            'nilai.*.max' => 'Nilai tidak boleh lebih dari :max.',
        ]);

        try {
            DB::transaction(function () use ($request, $siswa, $masterKriteria) {
                foreach ($masterKriteria as $kriteria) {
                    $nilai = $request->input("nilai.{$kriteria->id}");
                    
                    if ($nilai !== null) {
                        NilaiSiswa::updateOrCreate(
                            [
                                'siswa_id' => $siswa->id,
                                'master_kriteria_id' => $kriteria->id,
                            ],
                            [
                                'nilai' => $nilai,
                            ]
                        );
                    }
                }
            });

            return redirect()->route('panitia.nilai-jurusan.siswa', $jurusan)
                ->with('success', "Nilai untuk {$siswa->nama_lengkap} berhasil disimpan.");

        } catch (\Exception $e) {
            Log::error('Error saving nilai siswa: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan nilai.')
                ->withInput();
        }
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

        // Get all active master kriteria (global criteria)
        $masterKriteria = MasterKriteria::where('is_active', true)
            ->orderBy('kode_kriteria')
            ->get();

        if ($masterKriteria->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada kriteria yang aktif dalam sistem.');
        }

        // Get siswa for this jurusan
        $siswa = Siswa::where('pilihan_jurusan_1', $jurusan->id)
            ->where('tahun_akademik_id', $tahunAktif->id)
            ->with(['nilaiSiswa'])
            ->orderBy('nama_lengkap')
            ->get();

        // Prepare existing nilai data
        $existingNilai = [];
        foreach ($siswa as $s) {
            foreach ($s->nilaiSiswa as $nilai) {
                $existingNilai[$s->id][$nilai->master_kriteria_id] = $nilai->nilai;
            }
        }

        return view('panitia.nilai-jurusan.bulk-input', compact('jurusan', 'siswa', 'masterKriteria', 'existingNilai', 'tahunAktif'));
    }

    /**
     * Store bulk nilai for multiple siswa
     */
    public function storeBulkNilai(Request $request, Jurusan $jurusan)
    {
        $tahunAktif = TahunAkademik::where('is_active', true)->first();
        
        if (!$tahunAktif) {
            return redirect()->back()->with('error', 'Tidak ada tahun akademik yang aktif.');
        }

        // Get all active master kriteria for validation
        $masterKriteria = MasterKriteria::where('is_active', true)->get();

        // Build validation rules
        $rules = [];
        foreach ($masterKriteria as $kriteria) {
            $rules["nilai.*.{$kriteria->id}"] = [
                'nullable',
                'numeric',
                "min:{$kriteria->nilai_min}",
                "max:{$kriteria->nilai_max}"
            ];
        }

        $request->validate($rules, [
            'nilai.*.*.numeric' => 'Nilai harus berupa angka.',
            'nilai.*.*.min' => 'Nilai tidak boleh kurang dari :min.',
            'nilai.*.*.max' => 'Nilai tidak boleh lebih dari :max.',
        ]);

        try {
            $savedCount = 0;
            
            DB::transaction(function () use ($request, $jurusan, $masterKriteria, $tahunAktif, &$savedCount) {
                $nilaiData = $request->input('nilai', []);
                
                foreach ($nilaiData as $siswaId => $kriteriaValues) {
                    // Validate siswa belongs to this jurusan and tahun akademik
                    $siswa = Siswa::where('id', $siswaId)
                        ->where('pilihan_jurusan_1', $jurusan->id)
                        ->where('tahun_akademik_id', $tahunAktif->id)
                        ->first();
                    
                    if (!$siswa) {
                        continue; // Skip invalid siswa
                    }
                    
                    foreach ($masterKriteria as $kriteria) {
                        $nilai = $kriteriaValues[$kriteria->id] ?? null;
                        
                        if ($nilai !== null && $nilai !== '') {
                            NilaiSiswa::updateOrCreate(
                                [
                                    'siswa_id' => $siswa->id,
                                    'master_kriteria_id' => $kriteria->id,
                                ],
                                [
                                    'nilai' => $nilai,
                                ]
                            );
                            $savedCount++;
                        }
                    }
                }
            });

            return redirect()->route('panitia.nilai-jurusan.siswa', $jurusan)
                ->with('success', "Berhasil menyimpan {$savedCount} nilai siswa.");

        } catch (\Exception $e) {
            Log::error('Error saving bulk nilai siswa: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan nilai.')
                ->withInput();
        }
    }
}
