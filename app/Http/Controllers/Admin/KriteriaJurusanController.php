<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\MasterKriteria;
use App\Models\KriteriaJurusan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KriteriaJurusanController extends Controller
{
    /**
     * Display kriteria weights for a specific jurusan.
     */
    public function index(Request $request, Jurusan $jurusan)
    {
        $query = KriteriaJurusan::with('masterKriteria')
                               ->where('jurusan_id', $jurusan->id);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('masterKriteria', function ($q) use ($search) {
                $q->where('kode_kriteria', 'like', "%{$search}%")
                  ->orWhere('nama_kriteria', 'like', "%{$search}%");
            });
        }

        $kriteriaJurusan = $query->orderBy('id')->paginate(10);

        // Get available master kriteria that are not yet assigned to this jurusan
        $availableKriteria = MasterKriteria::whereNotIn('id', function($query) use ($jurusan) {
            $query->select('master_kriteria_id')
                  ->from('kriteria_jurusan')
                  ->where('jurusan_id', $jurusan->id);
        })->active()->get();

        // Get kriteria count for this jurusan
        $kriteriaCount = $kriteriaJurusan->total();

        return view('admin.kriteria-jurusan.index', compact(
            'jurusan',
            'kriteriaJurusan',
            'availableKriteria',
            'kriteriaCount'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Jurusan $jurusan)
    {
        $request->validate([
            'master_kriteria_id' => [
                'required',
                'exists:master_kriteria,id',
                Rule::unique('kriteria_jurusan')->where(function ($query) use ($jurusan) {
                    return $query->where('jurusan_id', $jurusan->id);
                }),
            ],
            'nilai_min' => 'required|numeric|min:0',
            'nilai_max' => 'required|numeric|min:0|gt:nilai_min',
            'is_active' => 'boolean',
        ]);

        // Validate rentang nilai
        $validationErrors = KriteriaJurusan::validateRentangNilai($request->nilai_min, $request->nilai_max);
        if (!empty($validationErrors)) {
            return back()->withErrors(['nilai_min' => implode(' ', $validationErrors)])->withInput();
        }

        // Note: Rentang nilai boleh tumpang tindih karena setiap kriteria memiliki sistem penilaian yang berbeda

        KriteriaJurusan::create([
            'master_kriteria_id' => $request->master_kriteria_id,
            'jurusan_id' => $jurusan->id,
            'nilai_min' => $request->nilai_min,
            'nilai_max' => $request->nilai_max,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.kriteria-jurusan.index', $jurusan)
                        ->with('success', 'Kriteria berhasil ditambahkan ke jurusan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jurusan $jurusan, KriteriaJurusan $kriteriaJurusan)
    {
        $request->validate([
            'nilai_min' => 'required|numeric|min:0',
            'nilai_max' => 'required|numeric|gt:nilai_min',
            'bobot' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'nullable|boolean'
        ], [
            'nilai_max.gt' => 'Nilai maksimum harus lebih besar dari nilai minimum.',
            'bobot.max' => 'Bobot tidak boleh lebih dari 100.',
            'bobot.min' => 'Bobot tidak boleh kurang dari 0.'
        ]);

        $kriteriaJurusan->update([
            'nilai_min' => $request->nilai_min,
            'nilai_max' => $request->nilai_max,
            'bobot' => $request->bobot ?? $kriteriaJurusan->bobot,
            'is_active' => $request->has('is_active') ? 1 : 0
        ]);

        return redirect()->route('admin.kriteria-jurusan.index', $jurusan)
            ->with('success', 'Kriteria jurusan berhasil diperbarui.');

        $request->validate([
            'nilai_min' => 'required|numeric|min:0',
            'nilai_max' => 'required|numeric|min:0|gt:nilai_min',
            'is_active' => 'boolean',
        ]);

        // Validate rentang nilai
        $validationErrors = KriteriaJurusan::validateRentangNilai($request->nilai_min, $request->nilai_max);
        if (!empty($validationErrors)) {
            return back()->withErrors(['nilai_min' => implode(' ', $validationErrors)])->withInput();
        }

        // Note: Rentang nilai boleh tumpang tindih karena setiap kriteria memiliki sistem penilaian yang berbeda

        $kriteriaJurusan->update([
            'nilai_min' => $request->nilai_min,
            'nilai_max' => $request->nilai_max,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.kriteria-jurusan.index', $jurusan)
                        ->with('success', 'Rentang nilai kriteria berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jurusan $jurusan, KriteriaJurusan $kriteriaJurusan)
    {
        // Check if kriteria is being used in nilai_siswa
        if ($kriteriaJurusan->masterKriteria->nilaiSiswa()->exists()) {
            return back()->withErrors([
                'error' => 'Kriteria tidak dapat dihapus karena sudah ada nilai siswa yang menggunakan kriteria ini.'
            ]);
        }

        $kriteriaJurusan->delete();

        return redirect()->route('admin.kriteria-jurusan.index', $jurusan)
                        ->with('success', 'Kriteria berhasil dihapus dari jurusan.');
    }

    /**
     * Toggle the status of the kriteria for this jurusan.
     */
    public function toggleStatus(Jurusan $jurusan, KriteriaJurusan $kriteriaJurusan)
    {
        $kriteriaJurusan->update([
            'is_active' => !$kriteriaJurusan->is_active
        ]);

        $status = $kriteriaJurusan->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Kriteria berhasil {$status} untuk jurusan ini.");
    }
}
