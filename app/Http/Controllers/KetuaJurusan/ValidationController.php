<?php

namespace App\Http\Controllers\KetuaJurusan;

use App\Http\Controllers\Controller;
use App\Models\PrometheusResult;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ValidationController extends Controller
{
    /**
     * Show validation dashboard for ketua jurusan
     */
    public function index()
    {
        $user = Auth::user();
        $tahunAkademik = TahunAkademik::getActive();

        if (!$user->jurusan_id) {
            return view('ketua-jurusan.validation.no-jurusan');
        }

        if (!$tahunAkademik) {
            return redirect()->route('ketua-jurusan.dashboard')
                ->with('error', 'Tidak ada tahun akademik yang aktif.');
        }

        // Get results for this jurusan that need validation
        // Only siswa who chose this jurusan as pilihan_jurusan_1
        // For kategori 'khusus': only those who passed ranking (masuk_kuota = true)
        // For kategori 'umum': all siswa who chose this jurusan
        $pendingResults = PrometheusResult::whereHas('siswa', function ($query) use ($user) {
                $query->where('pilihan_jurusan_1', $user->jurusan_id);
            })
            ->where('tahun_akademik_id', $tahunAkademik->id)
            ->where('status_validasi', 'pending')
            ->where(function ($query) {
                // For kategori 'khusus': only those who passed ranking
                $query->where('kategori', 'umum')
                      ->orWhere(function ($subQuery) {
                          $subQuery->where('kategori', 'khusus')
                                   ->where('masuk_kuota', true);
                      });
            })
            ->with(['siswa.pilihanJurusan1', 'siswa.pilihanJurusan2'])
            ->orderBy('ranking')
            ->get();

        $validatedResults = PrometheusResult::whereHas('siswa', function ($query) use ($user) {
                $query->where('pilihan_jurusan_1', $user->jurusan_id);
            })
            ->where('tahun_akademik_id', $tahunAkademik->id)
            ->where('status_validasi', '!=', 'pending')
            ->where(function ($query) {
                // For kategori 'khusus': only those who passed ranking
                $query->where('kategori', 'umum')
                      ->orWhere(function ($subQuery) {
                          $subQuery->where('kategori', 'khusus')
                                   ->where('masuk_kuota', true);
                      });
            })
            ->with(['siswa.pilihanJurusan1', 'siswa.pilihanJurusan2', 'validator'])
            ->orderBy('ranking')
            ->get();

        return view('ketua-jurusan.validation.index', compact(
            'pendingResults',
            'validatedResults',
            'tahunAkademik'
        ));
    }

    /**
     * Show validation form for specific result
     */
    public function show(PrometheusResult $result)
    {
        $user = Auth::user();

        // Check if this ketua jurusan can validate this result
        if (!$this->canValidateResult($result, $user)) {
            abort(403, 'Anda tidak memiliki akses untuk memvalidasi hasil ini.');
        }

        $result->load(['siswa.pilihanJurusan1', 'siswa.pilihanJurusan2', 'siswa.nilaiSiswa.masterKriteria']);

        return view('ketua-jurusan.validation.show', compact('result'));
    }

    /**
     * Validate a PROMETHEE result
     */
    public function validate(Request $request, PrometheusResult $result)
    {
        $user = Auth::user();

        // Check if this ketua jurusan can validate this result
        if (!$this->canValidateResult($result, $user)) {
            abort(403, 'Anda tidak memiliki akses untuk memvalidasi hasil ini.');
        }

        $validator = Validator::make($request->all(), [
            'status_validasi' => 'required|in:lulus,lulus_pilihan_2,tidak_lulus',
            'catatan_validasi' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update the result
        $result->update([
            'status_validasi' => $request->status_validasi,
            'catatan_validasi' => $request->catatan_validasi,
            'validated_by' => $user->id,
            'validated_at' => now(),
        ]);

        // Update siswa status and jurusan diterima based on validation
        $siswa = $result->siswa;

        switch ($request->status_validasi) {
            case 'lulus':
                $siswa->update([
                    'status_seleksi' => 'lulus',
                    'jurusan_diterima_id' => $siswa->pilihan_jurusan_1,
                ]);
                break;

            case 'lulus_pilihan_2':
                $siswa->update([
                    'status_seleksi' => 'lulus_pilihan_2',
                    'jurusan_diterima_id' => $siswa->pilihan_jurusan_2,
                ]);
                break;

            case 'tidak_lulus':
                $siswa->update([
                    'status_seleksi' => 'tidak_lulus',
                    'jurusan_diterima_id' => null,
                ]);
                break;
        }

        return redirect()->route('ketua-jurusan.validation.index')
            ->with('success', "Validasi untuk {$siswa->nama_lengkap} berhasil disimpan.");
    }

    /**
     * Bulk validation form
     */
    public function bulkForm()
    {
        $user = Auth::user();
        $tahunAkademik = TahunAkademik::getActive();

        if (!$user->jurusan_id || !$tahunAkademik) {
            return redirect()->route('ketua-jurusan.validation.index');
        }

        $pendingResults = PrometheusResult::whereHas('siswa', function ($query) use ($user) {
                $query->where('pilihan_jurusan_1', $user->jurusan_id);
            })
            ->where('tahun_akademik_id', $tahunAkademik->id)
            ->where('status_validasi', 'pending')
            ->where(function ($query) {
                // For kategori 'khusus': only those who passed ranking
                $query->where('kategori', 'umum')
                      ->orWhere(function ($subQuery) {
                          $subQuery->where('kategori', 'khusus')
                                   ->where('masuk_kuota', true);
                      });
            })
            ->with(['siswa.pilihanJurusan1', 'siswa.pilihanJurusan2'])
            ->orderBy('ranking')
            ->get();

        return view('ketua-jurusan.validation.bulk', compact('pendingResults'));
    }

    /**
     * Process bulk validation
     */
    public function bulkValidate(Request $request)
    {
        $user = Auth::user();
        $validations = $request->input('validations', []);

        if (empty($validations)) {
            return redirect()->back()
                ->with('error', 'Tidak ada data validasi yang dikirim.');
        }

        $successCount = 0;

        foreach ($validations as $resultId => $validation) {
            $result = PrometheusResult::find($resultId);

            if (!$result || !$this->canValidateResult($result, $user)) {
                continue;
            }

            if (empty($validation['status_validasi'])) {
                continue;
            }

            // Update the result
            $result->update([
                'status_validasi' => $validation['status_validasi'],
                'catatan_validasi' => $validation['catatan_validasi'] ?? null,
                'validated_by' => $user->id,
                'validated_at' => now(),
            ]);

            // Update siswa status
            $siswa = $result->siswa;
            switch ($validation['status_validasi']) {
                case 'lulus':
                    $siswa->update([
                        'status_seleksi' => 'lulus',
                        'jurusan_diterima_id' => $siswa->pilihan_jurusan_1,
                    ]);
                    break;

                case 'lulus_pilihan_2':
                    $siswa->update([
                        'status_seleksi' => 'lulus_pilihan_2',
                        'jurusan_diterima_id' => $siswa->pilihan_jurusan_2,
                    ]);
                    break;

                case 'tidak_lulus':
                    $siswa->update([
                        'status_seleksi' => 'tidak_lulus',
                        'jurusan_diterima_id' => null,
                    ]);
                    break;
            }

            $successCount++;
        }

        return redirect()->route('ketua-jurusan.validation.index')
            ->with('success', "Berhasil memvalidasi {$successCount} hasil seleksi.");
    }

    /**
     * Check if user can validate a specific result
     */
    private function canValidateResult(PrometheusResult $result, $user): bool
    {
        if (!$user->jurusan_id) {
            return false;
        }

        $siswa = $result->siswa;

        // Can only validate if siswa chose this jurusan as pilihan_jurusan_1
        if ($siswa->pilihan_jurusan_1 != $user->jurusan_id) {
            return false;
        }

        // For kategori 'khusus': only those who passed ranking (masuk_kuota = true)
        if ($result->kategori == 'khusus' && !$result->masuk_kuota) {
            return false;
        }

        return true;
    }
}
