<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahunAkademikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahunAkademik = TahunAkademik::orderBy('tahun', 'desc')->paginate(10);
        return view('admin.tahun-akademik.index', compact('tahunAkademik'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tahun-akademik.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|string|max:9|unique:tahun_akademik,tahun',
            'semester' => 'required|in:Ganjil,Genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($validated) {
            // If this is set as active, deactivate all others
            if (isset($validated['is_active']) && $validated['is_active']) {
                TahunAkademik::where('is_active', true)->update(['is_active' => false]);
            }

            TahunAkademik::create($validated);
        });

        return redirect()->route('admin.tahun-akademik.index')
            ->with('success', 'Tahun akademik berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TahunAkademik $tahunAkademik)
    {
        $tahunAkademik->load(['siswa', 'selectionProcessStatus']);
        return view('admin.tahun-akademik.show', compact('tahunAkademik'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TahunAkademik $tahunAkademik)
    {
        return view('admin.tahun-akademik.edit', compact('tahunAkademik'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TahunAkademik $tahunAkademik)
    {
        $validated = $request->validate([
            'tahun' => 'required|string|max:9|unique:tahun_akademik,tahun,' . $tahunAkademik->id,
            'semester' => 'required|in:Ganjil,Genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($validated, $tahunAkademik) {
            // If this is set as active, deactivate all others
            if (isset($validated['is_active']) && $validated['is_active']) {
                TahunAkademik::where('id', '!=', $tahunAkademik->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $tahunAkademik->update($validated);
        });

        return redirect()->route('admin.tahun-akademik.index')
            ->with('success', 'Tahun akademik berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TahunAkademik $tahunAkademik)
    {
        // Check if this tahun akademik has any siswa
        if ($tahunAkademik->siswa()->count() > 0) {
            return redirect()->route('admin.tahun-akademik.index')
                ->with('error', 'Tahun akademik tidak dapat dihapus karena masih memiliki data siswa.');
        }

        $tahunAkademik->delete();

        return redirect()->route('admin.tahun-akademik.index')
            ->with('success', 'Tahun akademik berhasil dihapus.');
    }

    /**
     * Set tahun akademik as active
     */
    public function setActive(TahunAkademik $tahunAkademik)
    {
        DB::transaction(function () use ($tahunAkademik) {
            // Deactivate all other tahun akademik
            TahunAkademik::where('id', '!=', $tahunAkademik->id)->update(['is_active' => false]);

            // Activate this one
            $tahunAkademik->update(['is_active' => true]);
        });

        return redirect()->route('admin.tahun-akademik.index')
            ->with('success', 'Tahun akademik ' . $tahunAkademik->tahun . ' berhasil diaktifkan.');
    }
}
