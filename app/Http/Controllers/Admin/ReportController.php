<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\PrometheusResult;
use App\Models\Jurusan;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Show statistics and reports
     */
    public function statistics()
    {
        $tahunAkademik = TahunAkademik::getActive();
        
        if (!$tahunAkademik) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Tidak ada tahun akademik yang aktif.');
        }

        // Overall statistics
        $totalSiswa = Siswa::where('tahun_akademik_id', $tahunAkademik->id)->count();
        $totalHasilSeleksi = PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)->count();
        $totalDiterima = PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)
            ->where('masuk_kuota', true)->count();

        // Statistics by category
        $statsKategori = [
            'khusus' => [
                'pendaftar' => Siswa::where('tahun_akademik_id', $tahunAkademik->id)
                    ->where('kategori', 'khusus')->count(),
                'diproses' => PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)
                    ->where('kategori', 'khusus')->count(),
                'diterima' => PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)
                    ->where('kategori', 'khusus')
                    ->where('masuk_kuota', true)->count(),
            ],
            'umum' => [
                'pendaftar' => Siswa::where('tahun_akademik_id', $tahunAkademik->id)
                    ->where('kategori', 'umum')->count(),
                'diproses' => PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)
                    ->where('kategori', 'umum')->count(),
                'diterima' => PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)
                    ->where('kategori', 'umum')
                    ->where('masuk_kuota', true)->count(),
            ]
        ];

        // Statistics by jurusan
        $statsJurusan = Jurusan::with(['prometheusResults' => function($query) use ($tahunAkademik) {
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
                    'total_diproses' => $jurusan->prometheusResults->count(),
                    'diterima' => $diterima,
                    'tidak_diterima' => $jurusan->prometheusResults->where('masuk_kuota', false)->count(),
                    'kuota' => $jurusan->kuota,
                    'sisa_kuota' => $jurusan->kuota - $diterima,
                    'persentase_terisi' => $jurusan->kuota > 0 ? round(($diterima / $jurusan->kuota) * 100, 2) : 0,
                    'rata_rata_skor' => $jurusan->prometheusResults->avg('phi_net') ?? 0
                ];
            });

        // Gender statistics
        $genderStats = Siswa::where('tahun_akademik_id', $tahunAkademik->id)
            ->select('jenis_kelamin', DB::raw('count(*) as total'))
            ->groupBy('jenis_kelamin')
            ->get()
            ->pluck('total', 'jenis_kelamin')
            ->toArray();

        // Monthly registration trend
        $monthlyTrend = Siswa::where('tahun_akademik_id', $tahunAkademik->id)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                $monthNames = [
                    1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
                    5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Ags',
                    9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
                ];
                return [
                    'month' => $monthNames[$item->month],
                    'total' => $item->total
                ];
            });

        return view('admin.reports.statistics', compact(
            'tahunAkademik', 'totalSiswa', 'totalHasilSeleksi', 'totalDiterima',
            'statsKategori', 'statsJurusan', 'genderStats', 'monthlyTrend'
        ));
    }

    /**
     * Export data functionality
     */
    public function export(Request $request)
    {
        $tahunAkademik = TahunAkademik::getActive();
        
        if (!$tahunAkademik) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Tidak ada tahun akademik yang aktif.');
        }

        // Available export options
        $exportOptions = [
            'siswa' => 'Data Siswa',
            'hasil_seleksi' => 'Hasil Seleksi',
            'ranking' => 'Ranking Siswa',
            'statistik' => 'Statistik Keseluruhan'
        ];

        return view('admin.reports.export', compact('tahunAkademik', 'exportOptions'));
    }
}
