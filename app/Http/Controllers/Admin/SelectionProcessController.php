<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\TahunAkademik;
use App\Models\PrometheusResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SelectionProcessController extends Controller
{
    /**
     * Show khusus category selection process overview
     */
    public function khusus()
    {
        $tahunAkademik = TahunAkademik::getActive();
        
        if (!$tahunAkademik) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Tidak ada tahun akademik yang aktif.');
        }

        // Get khusus category statistics
        $jurusanKhusus = Jurusan::where('kategori', 'khusus')->get();
        
        $statistics = [];
        foreach ($jurusanKhusus as $jurusan) {
            $totalPendaftar = Siswa::where('tahun_akademik_id', $tahunAkademik->id)
                ->where('kategori', 'khusus')
                ->where(function($q) use ($jurusan) {
                    $q->where('pilihan_jurusan_1', $jurusan->id)
                      ->orWhere('pilihan_jurusan_2', $jurusan->id);
                })
                ->count();

            $sudahDiproses = PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)
                ->where('kategori', 'khusus')
                ->whereHas('siswa', function($q) use ($jurusan) {
                    $q->where(function($sq) use ($jurusan) {
                        $sq->where('pilihan_jurusan_1', $jurusan->id)
                           ->orWhere('pilihan_jurusan_2', $jurusan->id);
                    });
                })
                ->count();

            $diterima = PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)
                ->where('kategori', 'khusus')
                ->where('masuk_kuota', true)
                ->whereHas('siswa', function($q) use ($jurusan) {
                    $q->where('jurusan_diterima_id', $jurusan->id);
                })
                ->count();

            $statistics[] = [
                'jurusan' => $jurusan,
                'total_pendaftar' => $totalPendaftar,
                'sudah_diproses' => $sudahDiproses,
                'diterima' => $diterima,
                'kuota' => $jurusan->kuota,
                'progress' => $totalPendaftar > 0 ? round(($sudahDiproses / $totalPendaftar) * 100, 2) : 0
            ];
        }

        return view('admin.selection-process.khusus', compact('statistics', 'tahunAkademik'));
    }

    /**
     * Show umum category selection process overview
     */
    public function umum()
    {
        $tahunAkademik = TahunAkademik::getActive();
        
        if (!$tahunAkademik) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Tidak ada tahun akademik yang aktif.');
        }

        // Get umum category statistics
        $jurusanUmum = Jurusan::where('kategori', 'umum')->get();
        
        $statistics = [];
        foreach ($jurusanUmum as $jurusan) {
            $totalPendaftar = Siswa::where('tahun_akademik_id', $tahunAkademik->id)
                ->where('kategori', 'umum')
                ->where(function($q) use ($jurusan) {
                    $q->where('pilihan_jurusan_1', $jurusan->id)
                      ->orWhere('pilihan_jurusan_2', $jurusan->id);
                })
                ->count();

            $sudahDiproses = PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)
                ->where('kategori', 'umum')
                ->whereHas('siswa', function($q) use ($jurusan) {
                    $q->where(function($sq) use ($jurusan) {
                        $sq->where('pilihan_jurusan_1', $jurusan->id)
                           ->orWhere('pilihan_jurusan_2', $jurusan->id);
                    });
                })
                ->count();

            $diterima = PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)
                ->where('kategori', 'umum')
                ->where('masuk_kuota', true)
                ->whereHas('siswa', function($q) use ($jurusan) {
                    $q->where('jurusan_diterima_id', $jurusan->id);
                })
                ->count();

            $statistics[] = [
                'jurusan' => $jurusan,
                'total_pendaftar' => $totalPendaftar,
                'sudah_diproses' => $sudahDiproses,
                'diterima' => $diterima,
                'kuota' => $jurusan->kuota,
                'progress' => $totalPendaftar > 0 ? round(($sudahDiproses / $totalPendaftar) * 100, 2) : 0
            ];
        }

        return view('admin.selection-process.umum', compact('statistics', 'tahunAkademik'));
    }

    /**
     * Show overall selection process monitoring
     */
    public function monitor()
    {
        $tahunAkademik = TahunAkademik::getActive();
        
        if (!$tahunAkademik) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Tidak ada tahun akademik yang aktif.');
        }

        // Overall statistics
        $totalSiswa = Siswa::where('tahun_akademik_id', $tahunAkademik->id)->count();
        $siswaKhusus = Siswa::where('tahun_akademik_id', $tahunAkademik->id)
            ->where('kategori', 'khusus')->count();
        $siswaUmum = Siswa::where('tahun_akademik_id', $tahunAkademik->id)
            ->where('kategori', 'umum')->count();

        $totalDiproses = PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)->count();
        $totalDiterima = PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)
            ->where('masuk_kuota', true)->count();

        // Progress by category
        $progressKhusus = $siswaKhusus > 0 ?
            round((PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)
                ->where('kategori', 'khusus')->count() / $siswaKhusus) * 100, 2) : 0;

        $progressUmum = $siswaUmum > 0 ?
            round((PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)
                ->where('kategori', 'umum')->count() / $siswaUmum) * 100, 2) : 0;

        // Recent activities (last 10 selection results)
        $recentActivities = PrometheusResult::with(['siswa'])
            ->where('tahun_akademik_id', $tahunAkademik->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Jurusan statistics
        $jurusanStats = Jurusan::with(['prometheusResults' => function($query) use ($tahunAkademik) {
                $query->where('tahun_akademik_id', $tahunAkademik->id);
            }])
            ->get()
            ->map(function($jurusan) use ($tahunAkademik) {
                $totalPendaftar = Siswa::where('tahun_akademik_id', $tahunAkademik->id)
                    ->where(function($q) use ($jurusan) {
                        $q->where('pilihan_jurusan_1', $jurusan->id)
                          ->orWhere('pilihan_jurusan_2', $jurusan->id);
                    })
                    ->count();

                $diterima = $jurusan->prometheusResults->where('masuk_kuota', true)->count();
                
                return [
                    'jurusan' => $jurusan,
                    'total_pendaftar' => $totalPendaftar,
                    'diterima' => $diterima,
                    'kuota' => $jurusan->kuota,
                    'sisa_kuota' => $jurusan->kuota - $diterima,
                    'persentase_terisi' => $jurusan->kuota > 0 ? round(($diterima / $jurusan->kuota) * 100, 2) : 0
                ];
            });

        return view('admin.selection-process.monitor', compact(
            'tahunAkademik', 'totalSiswa', 'siswaKhusus', 'siswaUmum',
            'totalDiproses', 'totalDiterima', 'progressKhusus', 'progressUmum',
            'recentActivities', 'jurusanStats'
        ));
    }
}
