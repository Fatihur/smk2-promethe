<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAkademik;
use App\Models\Siswa;
use App\Models\PrometheusResult;
use App\Exports\SiswaExport;
use App\Exports\HasilSeleksiExport;
use App\Exports\RankingExport;
use App\Exports\StatistikJurusanExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{


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

    /**
     * Export data siswa to Excel
     */
    public function exportSiswa(Request $request)
    {
        $tahunAkademik = TahunAkademik::getActive();

        if (!$tahunAkademik) {
            return redirect()->back()->with('error', 'Tidak ada tahun akademik yang aktif.');
        }

        $kategori = $request->input('kategori', 'all');
        $jurusanId = $request->input('jurusan_id');

        $filename = 'data_siswa_' . str_replace('/', '_', $tahunAkademik->tahun) . '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new SiswaExport($tahunAkademik->id, $kategori, $jurusanId), $filename);
    }

    /**
     * Export hasil seleksi to Excel
     */
    public function exportHasilSeleksi(Request $request)
    {
        $tahunAkademik = TahunAkademik::getActive();

        if (!$tahunAkademik) {
            return redirect()->back()->with('error', 'Tidak ada tahun akademik yang aktif.');
        }

        $kategori = $request->input('kategori', 'all');
        $jurusanId = $request->input('jurusan_id');

        $filename = 'hasil_seleksi_' . str_replace('/', '_', $tahunAkademik->tahun) . '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new HasilSeleksiExport($tahunAkademik->id, $kategori, $jurusanId), $filename);
    }

    /**
     * Export ranking siswa to Excel
     */
    public function exportRanking(Request $request)
    {
        $tahunAkademik = TahunAkademik::getActive();

        if (!$tahunAkademik) {
            return redirect()->back()->with('error', 'Tidak ada tahun akademik yang aktif.');
        }

        $kategori = $request->input('kategori', 'all');

        $filename = 'ranking_siswa_' . str_replace('/', '_', $tahunAkademik->tahun) . '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new RankingExport($tahunAkademik->id, $kategori), $filename);
    }

    /**
     * Export statistik keseluruhan to Excel
     */
    public function exportStatistik(Request $request)
    {
        $tahunAkademik = TahunAkademik::getActive();

        if (!$tahunAkademik) {
            return redirect()->back()->with('error', 'Tidak ada tahun akademik yang aktif.');
        }

        $filename = 'statistik_seleksi_' . str_replace('/', '_', $tahunAkademik->tahun) . '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new StatistikJurusanExport($tahunAkademik->id), $filename);
    }
}
