<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrometheusResult;
use App\Models\Jurusan;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * Display selection results overview
     */
    public function index(Request $request)
    {
        $tahunAkademik = TahunAkademik::getActive();
        
        if (!$tahunAkademik) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Tidak ada tahun akademik yang aktif.');
        }

        $query = PrometheusResult::with(['siswa', 'tahunAkademik'])
            ->where('tahun_akademik_id', $tahunAkademik->id);

        // Filter by jurusan
        if ($request->filled('jurusan_id')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('jurusan_diterima_id', $request->jurusan_id);
            });
        }

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status == 'diterima') {
                $query->where('masuk_kuota', true);
            } else {
                $query->where('masuk_kuota', false);
            }
        }

        // Search by student name or registration number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('no_pendaftaran', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $results = $query->orderBy('ranking')
                        ->orderBy('phi_net', 'desc')
                        ->paginate(20);

        // Get filter options
        $jurusan = Jurusan::orderBy('nama_jurusan')->get();
        $tahunAkademikList = TahunAkademik::orderBy('tahun', 'desc')->get();

        // Statistics
        $stats = [
            'total_hasil' => PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)->count(),
            'total_diterima' => PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)
                ->where('masuk_kuota', true)->count(),
            'total_tidak_diterima' => PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)
                ->where('masuk_kuota', false)->count(),
            'kategori_khusus' => PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)
                ->where('kategori', 'khusus')->count(),
            'kategori_umum' => PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)
                ->where('kategori', 'umum')->count(),
        ];

        return view('admin.results.index', compact(
            'results', 'jurusan', 'tahunAkademikList', 'tahunAkademik', 'stats'
        ));
    }
}
