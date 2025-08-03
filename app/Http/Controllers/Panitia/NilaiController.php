<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\NilaiSiswa;
use App\Models\KriteriaJurusan;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index(Siswa $siswa)
    {
        $nilai = $siswa->nilaiSiswa()->with('masterKriteria')->get();

        return view('panitia.siswa.nilai.index', compact('siswa', 'nilai'));
    }

    public function create(Siswa $siswa)
    {
        // Get kriteria for the student's chosen major (pilihan_jurusan_1)
        $kriteriaJurusan = KriteriaJurusan::where('jurusan_id', $siswa->pilihan_jurusan_1)
            ->where('is_active', true)
            ->with('masterKriteria')
            ->get();

        return view('panitia.siswa.nilai.create', compact('siswa', 'kriteriaJurusan'));
    }

    public function store(Request $request, Siswa $siswa)
    {
        // Get kriteria for validation
        $kriteriaJurusan = KriteriaJurusan::where('jurusan_id', $siswa->pilihan_jurusan_1)
            ->where('is_active', true)
            ->with('masterKriteria')
            ->get();

        // Build validation rules dynamically based on kriteria ranges
        $rules = ['nilai' => 'required|array'];
        foreach ($kriteriaJurusan as $kj) {
            $rules["nilai.{$kj->master_kriteria_id}"] = "required|numeric|min:{$kj->nilai_min}|max:{$kj->nilai_max}";
        }

        $request->validate($rules);

        foreach ($request->nilai as $masterKriteriaId => $nilaiValue) {
            NilaiSiswa::updateOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'master_kriteria_id' => $masterKriteriaId,
                ],
                [
                    'nilai' => $nilaiValue,
                ]
            );
        }

        return redirect()->route('panitia.siswa.nilai.index', $siswa)
            ->with('success', 'Nilai berhasil disimpan.');
    }

    public function edit(Siswa $siswa)
    {
        $kriteriaJurusan = KriteriaJurusan::where('jurusan_id', $siswa->pilihan_jurusan_1)
            ->where('is_active', true)
            ->with('masterKriteria')
            ->get();

        // Map kriteria for view compatibility
        $kriteria = $kriteriaJurusan->map(function($kj) {
            $mk = $kj->masterKriteria;
            $mk->nilai_min = $kj->nilai_min ?? 0; // Add nilai_min from kriteria_jurusan
            $mk->nilai_max = $kj->nilai_max ?? 100; // Add nilai_max from kriteria_jurusan
            return $mk;
        });

        // Get existing nilai indexed by master_kriteria_id
        $nilaiSiswa = [];
        $existingNilai = $siswa->nilaiSiswa()->with('masterKriteria')->get();
        foreach($existingNilai as $nilai) {
            $nilaiSiswa[$nilai->master_kriteria_id] = $nilai;
        }

        return view('panitia.siswa.nilai.edit', compact('siswa', 'kriteria', 'nilaiSiswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        // Get kriteria for validation
        $kriteriaJurusan = KriteriaJurusan::where('jurusan_id', $siswa->pilihan_jurusan_1)
            ->where('is_active', true)
            ->with('masterKriteria')
            ->get();

        // Build validation rules dynamically based on kriteria ranges
        $rules = ['nilai' => 'required|array'];
        foreach ($kriteriaJurusan as $kj) {
            $rules["nilai.{$kj->master_kriteria_id}"] = "required|numeric|min:{$kj->nilai_min}|max:{$kj->nilai_max}";
        }

        $request->validate($rules);

        foreach ($request->nilai as $masterKriteriaId => $nilaiValue) {
            NilaiSiswa::updateOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'master_kriteria_id' => $masterKriteriaId,
                ],
                [
                    'nilai' => $nilaiValue,
                ]
            );
        }

        return redirect()->route('panitia.siswa.nilai.index', $siswa)
            ->with('success', 'Nilai berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa, NilaiSiswa $nilai)
    {
        $nilai->delete();

        return redirect()->route('panitia.siswa.nilai.index', $siswa)
            ->with('success', 'Nilai berhasil dihapus.');
    }
}
