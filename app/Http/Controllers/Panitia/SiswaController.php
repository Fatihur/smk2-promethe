<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\TahunAkademik;
use App\Imports\SiswaImport;
use App\Exports\SiswaExport;
use App\Exports\SiswaTemplateExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Siswa::with(['pilihanJurusan1', 'pilihanJurusan2', 'tahunAkademik']);

        // Filter by kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_seleksi', $request->status);
        }

        // Filter by tahun akademik
        if ($request->filled('tahun_akademik_id')) {
            $query->where('tahun_akademik_id', $request->tahun_akademik_id);
        } else {
            // Default to active tahun akademik
            $activeTahun = TahunAkademik::getActive();
            if ($activeTahun) {
                $query->where('tahun_akademik_id', $activeTahun->id);
            }
        }

        $siswa = $query->orderBy('no_pendaftaran')->paginate(20);
        $tahunAkademik = TahunAkademik::all();

        return view('panitia.siswa.index', compact('siswa', 'tahunAkademik'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jurusan = Jurusan::active()->orderBy('nama_jurusan')->get();
        $tahunAkademik = TahunAkademik::getActive();

        if (!$tahunAkademik) {
            return redirect()->route('panitia.siswa.index')
                ->with('error', 'Tidak ada tahun akademik yang aktif. Hubungi administrator.');
        }

        return view('panitia.siswa.create', compact('jurusan', 'tahunAkademik'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_pendaftaran' => 'nullable|string|max:20',
            'nisn' => 'required|string|size:10|unique:siswa,nisn',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'alamat' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'nama_ayah' => 'nullable|string|max:255',
            'asal_sekolah' => 'required|string|max:255',
            'pilihan_jurusan_1' => 'required|exists:jurusan,id',
            'pilihan_jurusan_2' => 'nullable|exists:jurusan,id|different:pilihan_jurusan_1',
            'kategori' => 'required|in:umum,khusus',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $tahunAkademik = TahunAkademik::getActive();
        if (!$tahunAkademik) {
            return redirect()->back()
                ->with('error', 'Tidak ada tahun akademik yang aktif.')
                ->withInput();
        }

        // Generate no pendaftaran
        $noPendaftaran = Siswa::generateNoPendaftaran($tahunAkademik);

        // Determine kategori based on pilihan jurusan 1
        $jurusan1 = Jurusan::find($request->pilihan_jurusan_1);
        $kategori = in_array($jurusan1->kode_jurusan, ['TAB', 'TSM']) ? 'khusus' : 'umum';

        $siswa = Siswa::create([
            'no_pendaftaran' => $noPendaftaran,
            'nisn' => $request->nisn,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
            'asal_sekolah' => $request->asal_sekolah,
            'tahun_akademik_id' => $tahunAkademik->id,
            'pilihan_jurusan_1' => $request->pilihan_jurusan_1,
            'pilihan_jurusan_2' => $request->pilihan_jurusan_2,
            'kategori' => $kategori,
            'status_seleksi' => 'pending',
        ]);

        return redirect()->route('panitia.siswa.index')
            ->with('success', "Siswa berhasil ditambahkan dengan No. Pendaftaran: {$noPendaftaran}");
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        $siswa->load(['pilihanJurusan1', 'pilihanJurusan2', 'jurusanDiterima', 'tahunAkademik', 'nilaiSiswa.kriteria']);
        return view('panitia.siswa.show', compact('siswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        $jurusan = Jurusan::active()->orderBy('nama_jurusan')->get();
        return view('panitia.siswa.edit', compact('siswa', 'jurusan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $validator = Validator::make($request->all(), [
            'nisn' => 'required|string|size:10|unique:siswa,nisn,' . $siswa->id,
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'alamat' => 'required|string',
            'email' => 'nullable|email|max:255',
            'nama_ayah' => 'nullable|string|max:255',
            'asal_sekolah' => 'required|string|max:255',
            'pilihan_jurusan_1' => 'required|exists:jurusan,id',
            'pilihan_jurusan_2' => 'nullable|exists:jurusan,id|different:pilihan_jurusan_1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Determine new kategori based on pilihan jurusan 1
        $jurusan1 = Jurusan::find($request->pilihan_jurusan_1);
        $kategori = in_array($jurusan1->kode_jurusan, ['TAB', 'TSM']) ? 'khusus' : 'umum';

        $siswa->update([
            'nisn' => $request->nisn,
            'nama_lengkap' => $request->nama_lengkap,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
            'asal_sekolah' => $request->asal_sekolah,
            'pilihan_jurusan_1' => $request->pilihan_jurusan_1,
            'pilihan_jurusan_2' => $request->pilihan_jurusan_2,
            'kategori' => $kategori,
        ]);

        return redirect()->route('panitia.siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        // Check if siswa has nilai or PROMETHEE results
        if ($siswa->nilaiSiswa()->count() > 0 || $siswa->prometheusResults()->count() > 0) {
            return redirect()->route('panitia.siswa.index')
                ->with('error', 'Siswa tidak dapat dihapus karena sudah memiliki data nilai atau hasil PROMETHEE.');
        }

        $siswa->delete();

        return redirect()->route('panitia.siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    /**
     * Show import form
     */
    public function showImport()
    {
        $jurusan = Jurusan::where('is_active', true)->get();
        return view('panitia.siswa.import', compact('jurusan'));
    }

    /**
     * Import siswa from Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $import = new SiswaImport();
            Excel::import($import, $request->file('file'));

            $stats = $import->getImportStats();

            $message = "Import selesai! ";
            $message .= "Berhasil: {$stats['imported']}, ";
            $message .= "Dilewati: {$stats['skipped']}, ";
            $message .= "Error: {$stats['errors']}";

            if ($stats['failures'] > 0) {
                $message .= ", Gagal validasi: {$stats['failures']}";
            }

            // Add detailed explanation
            if ($stats['skipped'] > 0) {
                $message .= ". Data dilewati karena: NISN kosong, nama kosong, atau data duplikat.";
            }

            if ($stats['failures'] > 0) {
                $message .= " Gagal validasi karena: NISN tidak 10 digit atau jurusan tidak valid.";
            }

            return redirect()->route('panitia.siswa.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengimport file: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Download template Excel
     */
    public function downloadTemplate()
    {
        $filename = 'template_import_siswa.xlsx';
        return Excel::download(new SiswaTemplateExport(), $filename);
    }

    /**
     * Export siswa data to Excel
     */
    public function export(Request $request)
    {
        $tahunAkademik = TahunAkademik::where('is_active', true)->first();
        $kategori = $request->input('kategori', 'all');
        $jurusanId = $request->input('jurusan_id');

        $filename = 'data_siswa_' . str_replace('/', '_', $tahunAkademik->tahun_akademik) . '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new SiswaExport($tahunAkademik->id, $kategori, $jurusanId), $filename);
    }
}
