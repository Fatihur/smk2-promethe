<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

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
}
