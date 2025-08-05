<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Services\PrometheusService;
use App\Models\TahunAkademik;
use App\Models\Siswa;
use App\Models\PrometheusResult;
use App\Models\MasterKriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PrometheusController extends Controller
{
    protected $prometheusService;

    public function __construct(PrometheusService $prometheusService)
    {
        $this->prometheusService = $prometheusService;
    }

    /**
     * Show PROMETHEE dashboard
     */
    public function index()
    {
        $tahunAkademik = TahunAkademik::getActive();

        if (!$tahunAkademik) {
            return redirect()->route('panitia.dashboard')
                ->with('error', 'Tidak ada tahun akademik yang aktif.');
        }

        // Get statistics
        $stats = [
            'total_siswa' => Siswa::where('tahun_akademik_id', $tahunAkademik->id)->count(),
            'khusus_count' => Siswa::where('tahun_akademik_id', $tahunAkademik->id)->where('kategori', 'khusus')->count(),
            'umum_count' => Siswa::where('tahun_akademik_id', $tahunAkademik->id)->where('kategori', 'umum')->count(),
        ];

        // Check khusus validation status for umum process
        $khususValidationStatus = $this->checkKhususValidationStatus($tahunAkademik);

        return view('panitia.promethee.index', compact('tahunAkademik', 'stats', 'khususValidationStatus'));
    }

    /**
     * Show form to run PROMETHEE for Khusus category
     */
    public function khususForm()
    {
        $tahunAkademik = TahunAkademik::getActive();
        $khususCount = Siswa::where('tahun_akademik_id', $tahunAkademik->id)
            ->where('kategori', 'khusus')
            ->count();

        return view('panitia.promethee.khusus-form', compact('tahunAkademik', 'khususCount'));
    }

    /**
     * Run PROMETHEE calculation for Khusus category
     */
    public function runKhusus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kuota' => 'required|integer|min:1|max:200'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $result = $this->prometheusService->calculateRanking('khusus', null, $request->kuota);

        if (isset($result['error'])) {
            return redirect()->back()
                ->with('error', $result['error'])
                ->withInput();
        }

        return redirect()->route('panitia.promethee.khusus.results')
            ->with('success', 'PROMETHEE untuk kategori Khusus berhasil dijalankan!');
    }

    /**
     * Show PROMETHEE results for Khusus category
     */
    public function khususResults()
    {
        $results = $this->prometheusService->getResults('khusus');

        if (empty($results)) {
            return redirect()->route('panitia.promethee.khusus.form')
                ->with('error', 'Belum ada hasil PROMETHEE untuk kategori Khusus.');
        }

        // Get active kriteria with weights for display
        $kriteria = MasterKriteria::where('is_active', true)
            ->orderBy('kode_kriteria')
            ->get();

        return view('panitia.promethee.khusus-results', compact('results', 'kriteria'));
    }

    /**
     * Transfer failed Khusus students to Umum
     */
    public function transferToUmum()
    {
        $result = $this->prometheusService->transferFailedKhususToUmum();

        return redirect()->route('panitia.promethee.index')
            ->with('success', $result['message']);
    }

    /**
     * Show form to run PROMETHEE for Umum category
     */
    public function umumForm()
    {
        $tahunAkademik = TahunAkademik::getActive();

        // Check if khusus process is completed and validated
        $khususValidationStatus = $this->checkKhususValidationStatus($tahunAkademik);

        $umumCount = Siswa::where('tahun_akademik_id', $tahunAkademik->id)
            ->where('kategori', 'umum')
            ->count();

        return view('panitia.promethee.umum-form', compact(
            'tahunAkademik',
            'umumCount',
            'khususValidationStatus'
        ));
    }

    /**
     * Run PROMETHEE calculation for Umum category
     */
    public function runUmum(Request $request)
    {
        $tahunAkademik = TahunAkademik::getActive();

        // Check if khusus process is completed and validated
        $khususValidationStatus = $this->checkKhususValidationStatus($tahunAkademik);

        if (!$khususValidationStatus['can_proceed']) {
            return redirect()->back()
                ->with('error', $khususValidationStatus['message']);
        }

        $result = $this->prometheusService->calculateRanking('umum');

        if (isset($result['error'])) {
            return redirect()->back()
                ->with('error', $result['error']);
        }

        return redirect()->route('panitia.promethee.umum.results')
            ->with('success', 'PROMETHEE untuk kategori Umum berhasil dijalankan!');
    }

    /**
     * Show PROMETHEE results for Umum category
     */
    public function umumResults()
    {
        $results = $this->prometheusService->getResults('umum');

        if (empty($results)) {
            return redirect()->route('panitia.promethee.umum.form')
                ->with('error', 'Belum ada hasil PROMETHEE untuk kategori Umum.');
        }

        // Get active kriteria with weights for display
        $kriteria = MasterKriteria::where('is_active', true)
            ->orderBy('kode_kriteria')
            ->get();

        return view('panitia.promethee.umum-results', compact('results', 'kriteria'));
    }

    /**
     * Check if khusus validation process is completed
     */
    private function checkKhususValidationStatus($tahunAkademik): array
    {
        if (!$tahunAkademik) {
            return [
                'can_proceed' => false,
                'message' => 'Tidak ada tahun akademik yang aktif.',
                'has_khusus_results' => false,
                'pending_validations' => 0,
                'completed_validations' => 0
            ];
        }

        // Check if there are any khusus results
        $khususResults = PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)
            ->where('kategori', 'khusus')
            ->count();

        if ($khususResults === 0) {
            return [
                'can_proceed' => false,
                'message' => 'Proses PROMETHEE kategori khusus belum dijalankan. Silakan jalankan proses kategori khusus terlebih dahulu.',
                'has_khusus_results' => false,
                'pending_validations' => 0,
                'completed_validations' => 0
            ];
        }

        // Check validation status for khusus results that are in kuota (need validation)
        $khususInKuota = PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)
            ->where('kategori', 'khusus')
            ->where('masuk_kuota', true)
            ->count();

        $pendingValidations = PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)
            ->where('kategori', 'khusus')
            ->where('masuk_kuota', true)
            ->where('status_validasi', 'pending')
            ->count();

        $completedValidations = PrometheusResult::where('tahun_akademik_id', $tahunAkademik->id)
            ->where('kategori', 'khusus')
            ->where('masuk_kuota', true)
            ->where('status_validasi', '!=', 'pending')
            ->count();

        if ($pendingValidations > 0) {
            return [
                'can_proceed' => false,
                'message' => "Masih ada {$pendingValidations} siswa kategori khusus yang belum divalidasi oleh Ketua Jurusan. Proses PROMETHEE umum tidak dapat dijalankan sampai semua validasi kategori khusus selesai.",
                'has_khusus_results' => true,
                'pending_validations' => $pendingValidations,
                'completed_validations' => $completedValidations,
                'total_in_kuota' => $khususInKuota
            ];
        }

        return [
            'can_proceed' => true,
            'message' => 'Semua validasi kategori khusus telah selesai. PROMETHEE umum dapat dijalankan.',
            'has_khusus_results' => true,
            'pending_validations' => 0,
            'completed_validations' => $completedValidations,
            'total_in_kuota' => $khususInKuota
        ];
    }
}
