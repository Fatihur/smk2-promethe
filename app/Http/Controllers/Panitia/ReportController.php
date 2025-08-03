<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\TahunAkademik;
use App\Models\Siswa;
use App\Models\PrometheusResult;
use App\Models\Jurusan;
use App\Services\StatusTrackingService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HasilSeleksiExport;
use App\Exports\StatistikJurusanExport;

class ReportController extends Controller
{
    protected $statusService;

    public function __construct(StatusTrackingService $statusService)
    {
        $this->statusService = $statusService;
    }

    /**
     * Show reports dashboard
     */
    public function index()
    {
        $tahunAkademik = TahunAkademik::getActive();
        $stats = $this->statusService->getDashboardStats();

        return view('panitia.reports.index', compact('tahunAkademik', 'stats'));
    }

    /**
     * Generate and display hasil seleksi report
     */
    public function hasilSeleksi(Request $request)
    {
        $tahunAkademik = TahunAkademik::getActive();
        $kategori = $request->input('kategori', 'all');
        $jurusanId = $request->input('jurusan_id');

        $query = Siswa::with(['pilihanJurusan1', 'pilihanJurusan2', 'jurusanDiterima', 'prometheusResults'])
            ->where('tahun_akademik_id', $tahunAkademik->id);

        if ($kategori !== 'all') {
            $query->where('kategori', $kategori);
        }

        if ($jurusanId) {
            $query->where(function($q) use ($jurusanId) {
                $q->where('pilihan_jurusan_1', $jurusanId)
                  ->orWhere('jurusan_diterima_id', $jurusanId);
            });
        }

        $siswa = $query->orderBy('no_pendaftaran')->get();
        $jurusan = Jurusan::active()->orderBy('nama_jurusan')->get();

        return view('panitia.reports.hasil-seleksi', compact('siswa', 'jurusan', 'tahunAkademik', 'kategori', 'jurusanId'));
    }

    /**
     * Generate and display ranking report
     */
    public function ranking(Request $request)
    {
        $tahunAkademik = TahunAkademik::getActive();
        $kategori = $request->input('kategori', 'khusus');

        $results = PrometheusResult::with(['siswa.pilihanJurusan1', 'siswa.pilihanJurusan2', 'siswa.jurusanDiterima'])
            ->where('tahun_akademik_id', $tahunAkademik->id)
            ->where('kategori', $kategori)
            ->orderBy('ranking')
            ->get();

        return view('panitia.reports.ranking', compact('results', 'tahunAkademik', 'kategori'));
    }

    /**
     * Generate and display statistics report
     */
    public function statistik()
    {
        $tahunAkademik = TahunAkademik::getActive();
        $stats = $this->statusService->getDashboardStats();

        return view('panitia.reports.statistik', compact('tahunAkademik', 'stats'));
    }

    /**
     * Generate daftar lulus report
     */
    public function daftarLulus(Request $request)
    {
        $tahunAkademik = TahunAkademik::getActive();
        $jurusanId = $request->input('jurusan_id');

        $query = Siswa::with(['pilihanJurusan1', 'pilihanJurusan2', 'jurusanDiterima'])
            ->where('tahun_akademik_id', $tahunAkademik->id)
            ->whereIn('status_seleksi', ['lulus', 'lulus_pilihan_2']);

        if ($jurusanId) {
            $query->where('jurusan_diterima_id', $jurusanId);
        }

        $siswaLulus = $query->orderBy('nama_lengkap')->get();
        $jurusan = Jurusan::active()->orderBy('nama_jurusan')->get();

        return view('panitia.reports.daftar-lulus', compact('siswaLulus', 'jurusan', 'tahunAkademik', 'jurusanId'));
    }

    /**
     * Print hasil seleksi
     */
    public function printHasilSeleksi(Request $request)
    {
        $tahunAkademik = TahunAkademik::getActive();
        $kategori = $request->input('kategori', 'all');
        $jurusanId = $request->input('jurusan_id');

        $query = Siswa::with(['pilihanJurusan1', 'pilihanJurusan2', 'jurusanDiterima', 'prometheusResults'])
            ->where('tahun_akademik_id', $tahunAkademik->id);

        if ($kategori !== 'all') {
            $query->where('kategori', $kategori);
        }

        if ($jurusanId) {
            $query->where(function($q) use ($jurusanId) {
                $q->where('pilihan_jurusan_1', $jurusanId)
                  ->orWhere('jurusan_diterima_id', $jurusanId);
            });
        }

        $siswa = $query->orderBy('no_pendaftaran')->get();
        $jurusanName = $jurusanId ? Jurusan::find($jurusanId)->nama_jurusan : 'Semua Jurusan';

        return view('panitia.reports.print.hasil-seleksi', compact('siswa', 'tahunAkademik', 'kategori', 'jurusanName'));
    }

    /**
     * Print ranking report
     */
    public function printRanking(Request $request)
    {
        $tahunAkademik = TahunAkademik::getActive();
        $kategori = $request->input('kategori', 'khusus');

        $results = PrometheusResult::with(['siswa.pilihanJurusan1', 'siswa.pilihanJurusan2'])
            ->where('tahun_akademik_id', $tahunAkademik->id)
            ->where('kategori', $kategori)
            ->orderBy('ranking')
            ->get();

        return view('panitia.reports.print.ranking', compact('results', 'tahunAkademik', 'kategori'));
    }

    /**
     * Print daftar lulus
     */
    public function printDaftarLulus(Request $request)
    {
        $tahunAkademik = TahunAkademik::getActive();
        $jurusanId = $request->input('jurusan_id');

        $query = Siswa::with(['pilihanJurusan1', 'pilihanJurusan2', 'jurusanDiterima'])
            ->where('tahun_akademik_id', $tahunAkademik->id)
            ->whereIn('status_seleksi', ['lulus', 'lulus_pilihan_2']);

        if ($jurusanId) {
            $query->where('jurusan_diterima_id', $jurusanId);
        }

        $siswaLulus = $query->orderBy('nama_lengkap')->get();
        $jurusanName = $jurusanId ? Jurusan::find($jurusanId)->nama_jurusan : 'Semua Jurusan';

        return view('panitia.reports.print.daftar-lulus', compact('siswaLulus', 'tahunAkademik', 'jurusanName'));
    }

    /**
     * Export hasil seleksi to Excel
     */
    public function exportHasilSeleksi(Request $request)
    {
        $tahunAkademik = TahunAkademik::getActive();
        $kategori = $request->input('kategori', 'all');
        $jurusanId = $request->input('jurusan_id');

        $filename = 'hasil_seleksi_' . str_replace('/', '_', $tahunAkademik->tahun) . '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new HasilSeleksiExport($tahunAkademik->id, $kategori, $jurusanId), $filename);
    }

    /**
     * Export statistik to Excel
     */
    public function exportStatistik()
    {
        $tahunAkademik = TahunAkademik::getActive();
        $filename = 'statistik_seleksi_' . str_replace('/', '_', $tahunAkademik->tahun) . '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new StatistikJurusanExport($tahunAkademik->id), $filename);
    }
}
