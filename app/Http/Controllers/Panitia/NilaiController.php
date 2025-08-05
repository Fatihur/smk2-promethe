<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\NilaiSiswa;
use App\Models\MasterKriteria;
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
        // Get all active master kriteria (global criteria)
        $masterKriteria = MasterKriteria::where('is_active', true)
            ->orderBy('kode_kriteria')
            ->get();

        return view('panitia.siswa.nilai.create', compact('siswa', 'masterKriteria'));
    }

    public function store(Request $request, Siswa $siswa)
    {
        // Get all active master kriteria for validation
        $masterKriteria = MasterKriteria::where('is_active', true)->get();

        // Build validation rules
        $rules = [];
        foreach ($masterKriteria as $kriteria) {
            $rules["nilai.{$kriteria->id}"] = [
                'required',
                'numeric',
                "min:{$kriteria->nilai_min}",
                "max:{$kriteria->nilai_max}"
            ];
        }

        $request->validate($rules, [
            'nilai.*.required' => 'Nilai kriteria harus diisi.',
            'nilai.*.numeric' => 'Nilai harus berupa angka.',
            'nilai.*.min' => 'Nilai tidak boleh kurang dari :min.',
            'nilai.*.max' => 'Nilai tidak boleh lebih dari :max.',
        ]);

        // Save nilai
        foreach ($masterKriteria as $kriteria) {
            $nilai = $request->input("nilai.{$kriteria->id}");
            
            if ($nilai !== null) {
                NilaiSiswa::updateOrCreate(
                    [
                        'siswa_id' => $siswa->id,
                        'master_kriteria_id' => $kriteria->id,
                    ],
                    [
                        'nilai' => $nilai,
                    ]
                );
            }
        }

        return redirect()->route('panitia.siswa.nilai.index', $siswa)
            ->with('success', 'Nilai berhasil disimpan.');
    }

    public function edit(Siswa $siswa)
    {
        // Get all active master kriteria (global criteria)
        $masterKriteria = MasterKriteria::where('is_active', true)
            ->orderBy('kode_kriteria')
            ->get();

        // Get existing nilai
        $existingNilai = [];
        $nilai = $siswa->nilaiSiswa()->with('masterKriteria')->get();
        foreach($nilai as $n) {
            $existingNilai[$n->master_kriteria_id] = $n;
        }

        return view('panitia.siswa.nilai.edit', compact('siswa', 'masterKriteria', 'existingNilai'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        // Get all active master kriteria for validation
        $masterKriteria = MasterKriteria::where('is_active', true)->get();

        // Build validation rules
        $rules = [];
        foreach ($masterKriteria as $kriteria) {
            $rules["nilai.{$kriteria->id}"] = [
                'required',
                'numeric',
                "min:{$kriteria->nilai_min}",
                "max:{$kriteria->nilai_max}"
            ];
        }

        $request->validate($rules, [
            'nilai.*.required' => 'Nilai kriteria harus diisi.',
            'nilai.*.numeric' => 'Nilai harus berupa angka.',
            'nilai.*.min' => 'Nilai tidak boleh kurang dari :min.',
            'nilai.*.max' => 'Nilai tidak boleh lebih dari :max.',
        ]);

        // Update nilai
        foreach ($masterKriteria as $kriteria) {
            $nilai = $request->input("nilai.{$kriteria->id}");
            
            if ($nilai !== null) {
                NilaiSiswa::updateOrCreate(
                    [
                        'siswa_id' => $siswa->id,
                        'master_kriteria_id' => $kriteria->id,
                    ],
                    [
                        'nilai' => $nilai,
                    ]
                );
            }
        }

        return redirect()->route('panitia.siswa.nilai.index', $siswa)
            ->with('success', 'Nilai berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa, NilaiSiswa $nilai)
    {
        // Verify that the nilai belongs to the siswa
        if ($nilai->siswa_id !== $siswa->id) {
            return redirect()->back()->with('error', 'Nilai tidak ditemukan.');
        }

        $nilai->delete();

        return redirect()->route('panitia.siswa.nilai.index', $siswa)
            ->with('success', 'Nilai berhasil dihapus.');
    }
}
