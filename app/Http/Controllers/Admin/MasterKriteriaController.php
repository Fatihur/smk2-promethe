<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterKriteria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MasterKriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MasterKriteria::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_kriteria', 'like', "%{$search}%")
                  ->orWhere('nama_kriteria', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $masterKriteria = $query->orderBy('kode_kriteria')
                               ->paginate(10);

        return view('admin.master-kriteria.index', compact('masterKriteria'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.master-kriteria.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_kriteria' => 'required|string|max:10|unique:master_kriteria,kode_kriteria',
            'nama_kriteria' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:benefit,cost',
            'bobot' => 'required|numeric|min:0.01|max:100',
            'nilai_min' => 'required|numeric|min:0',
            'nilai_max' => 'required|numeric|min:1|gt:nilai_min',
            'is_active' => 'boolean',
        ]);

        MasterKriteria::create($request->all());

        return redirect()->route('admin.master-kriteria.index')
                        ->with('success', 'Master kriteria berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MasterKriteria $masterKriterium)
    {
        $nilaiSiswaCount = $masterKriterium->nilaiSiswa()->count();

        return view('admin.master-kriteria.show', compact(
            'masterKriterium',
            'nilaiSiswaCount'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MasterKriteria $masterKriterium)
    {
        return view('admin.master-kriteria.edit', compact('masterKriterium'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MasterKriteria $masterKriterium)
    {
        $request->validate([
            'kode_kriteria' => [
                'required',
                'string',
                'max:10',
                Rule::unique('master_kriteria')->ignore($masterKriterium->id),
            ],
            'nama_kriteria' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:benefit,cost',
            'bobot' => 'required|numeric|min:0.01|max:100',
            'nilai_min' => 'required|numeric|min:0',
            'nilai_max' => 'required|numeric|min:1|gt:nilai_min',
            'is_active' => 'boolean',
        ]);

        $masterKriterium->update($request->all());

        return redirect()->route('admin.master-kriteria.index')
                        ->with('success', 'Master kriteria berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MasterKriteria $masterKriterium)
    {
        // Check if kriteria is being used by any jurusan
        $usageCount = $masterKriterium->kriteriaJurusan()->count();
        $jurusanNames = $masterKriterium->jurusans()->pluck('nama_jurusan')->toArray();

        if ($usageCount > 0) {
            $jurusanList = implode(', ', $jurusanNames);
            return back()->withErrors([
                'error' => "Kriteria tidak dapat dihapus karena masih digunakan oleh {$usageCount} jurusan: {$jurusanList}. Hapus kriteria dari jurusan tersebut terlebih dahulu."
            ]);
        }

        // Check if kriteria is being used in nilai_siswa
        if ($masterKriterium->nilaiSiswa()->exists()) {
            return back()->withErrors([
                'error' => 'Kriteria tidak dapat dihapus karena sudah ada data nilai siswa yang menggunakan kriteria ini.'
            ]);
        }

        $masterKriterium->delete();

        return redirect()->route('admin.master-kriteria.index')
                        ->with('success', 'Master kriteria berhasil dihapus.');
    }

    /**
     * Force delete kriteria and all its related data.
     */
    public function forceDestroy(MasterKriteria $masterKriterium)
    {
        $usageCount = $masterKriterium->kriteriaJurusan()->count();
        $nilaiSiswaCount = $masterKriterium->nilaiSiswa()->count();

        // Delete all related data first
        if ($nilaiSiswaCount > 0) {
            $masterKriterium->nilaiSiswa()->delete();
        }

        if ($usageCount > 0) {
            $masterKriterium->kriteriaJurusan()->delete();
        }

        // Delete the kriteria itself
        $masterKriterium->delete();

        return redirect()->route('admin.master-kriteria.index')
                        ->with('success', "Master kriteria berhasil dihapus beserta {$usageCount} penggunaan di jurusan dan {$nilaiSiswaCount} data nilai siswa.");
    }

    /**
     * Toggle the status of the kriteria.
     */
    public function toggleStatus(MasterKriteria $masterKriterium)
    {
        $masterKriterium->update([
            'is_active' => !$masterKriterium->is_active
        ]);

        $status = $masterKriterium->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Kriteria berhasil {$status}.");
    }
}
