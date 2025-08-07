<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAkademik;
use App\Http\Requests\TahunAkademikRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TahunAkademikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TahunAkademik::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('tahun', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filter by year range
        if ($request->filled('year_from')) {
            $query->where('tahun', '>=', $request->get('year_from'));
        }

        if ($request->filled('year_to')) {
            $query->where('tahun', '<=', $request->get('year_to'));
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'tahun');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['tahun', 'tanggal_mulai', 'tanggal_selesai', 'is_active', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('tahun', 'desc');
        }

        // Load relationships and paginate
        $tahunAkademik = $query->withCount('siswa')->paginate(10)->appends($request->query());

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
    public function store(TahunAkademikRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated) {
                // Convert is_active to boolean
                $validated['is_active'] = isset($validated['is_active']) && $validated['is_active'] == '1';

                // Always set semester to 'Ganjil'
                $validated['semester'] = 'Ganjil';

                // If this is set as active, deactivate all others
                if ($validated['is_active']) {
                    TahunAkademik::where('is_active', true)->update(['is_active' => false]);
                }

                TahunAkademik::create($validated);
            });

            return redirect()->route('admin.tahun-akademik.index')
                ->with('success', 'Tahun akademik berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating tahun akademik', [
                'error' => $e->getMessage(),
                'input' => $validated
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TahunAkademik $tahun_akademik)
    {
        $tahun_akademik->load(['siswa', 'selectionProcessStatus']);
        return view('admin.tahun-akademik.show', compact('tahun_akademik'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TahunAkademik $tahun_akademik)
    {
        return view('admin.tahun-akademik.edit', compact('tahun_akademik'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TahunAkademikRequest $request, TahunAkademik $tahun_akademik)
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated, $tahun_akademik) {
                // Convert is_active to boolean
                $validated['is_active'] = isset($validated['is_active']) && $validated['is_active'] == '1';

                // Always set semester to 'Ganjil'
                $validated['semester'] = 'Ganjil';

                // If this is set as active, deactivate all others
                if ($validated['is_active']) {
                    TahunAkademik::where('id', '!=', $tahun_akademik->id)
                        ->where('is_active', true)
                        ->update(['is_active' => false]);
                }

                $tahun_akademik->update($validated);
            });

            return redirect()->route('admin.tahun-akademik.index')
                ->with('success', 'Tahun akademik berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating tahun akademik', [
                'error' => $e->getMessage(),
                'tahun_akademik_id' => $tahun_akademik->id,
                'input' => $validated
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TahunAkademik $tahun_akademik)
    {
        Log::info('Delete request received for tahun akademik', [
            'id' => $tahun_akademik->id,
            'tahun' => $tahun_akademik->tahun,
            'semester' => $tahun_akademik->semester,
            'is_active' => $tahun_akademik->is_active
        ]);

        try {
            // Check if this is the active tahun akademik
            if ($tahun_akademik->is_active) {
                return redirect()->route('admin.tahun-akademik.index')
                    ->with('error', 'Tahun akademik aktif tidak dapat dihapus. Silakan nonaktifkan terlebih dahulu.');
            }

            // Check if this tahun akademik has any siswa
            $siswaCount = $tahun_akademik->siswa()->count();
            if ($siswaCount > 0) {
                return redirect()->route('admin.tahun-akademik.index')
                    ->with('error', "Tahun akademik tidak dapat dihapus karena masih memiliki {$siswaCount} data siswa.");
            }

            // Check if this tahun akademik has any related data
            $relatedDataCount = 0;

            // Check for promethee results if the table exists
            try {
                $relatedDataCount += DB::table('promethee_results')
                    ->where('tahun_akademik_id', $tahun_akademik->id)
                    ->count();
            } catch (\Exception $e) {
                Log::warning('Error checking promethee results', ['error' => $e->getMessage()]);
            }

            // Check for selection process status if the table exists
            try {
                $relatedDataCount += DB::table('selection_process_status')
                    ->where('tahun_akademik_id', $tahun_akademik->id)
                    ->count();
            } catch (\Exception $e) {
                Log::warning('Error checking selection process status', ['error' => $e->getMessage()]);
                // If table doesn't exist, we can safely ignore this check
            }

            if ($relatedDataCount > 0) {
                return redirect()->route('admin.tahun-akademik.index')
                    ->with('error', 'Tahun akademik tidak dapat dihapus karena masih memiliki data terkait lainnya.');
            }

            // Check if this is the only tahun akademik
            $totalTahunAkademik = TahunAkademik::count();
            if ($totalTahunAkademik <= 1) {
                return redirect()->route('admin.tahun-akademik.index')
                    ->with('error', 'Tidak dapat menghapus tahun akademik terakhir. Sistem harus memiliki minimal satu tahun akademik.');
            }

            DB::transaction(function () use ($tahun_akademik) {
                // Delete related selection process status if exists
                try {
                    DB::table('selection_process_status')
                        ->where('tahun_akademik_id', $tahun_akademik->id)
                        ->delete();
                } catch (\Exception $e) {
                    Log::warning('Error deleting selection process status', ['error' => $e->getMessage()]);
                    // Continue with deletion even if this fails
                }

                // Delete related promethee results if exists
                try {
                    DB::table('promethee_results')
                        ->where('tahun_akademik_id', $tahun_akademik->id)
                        ->delete();
                } catch (\Exception $e) {
                    Log::warning('Error deleting promethee results', ['error' => $e->getMessage()]);
                    // Continue with deletion even if this fails
                }

                // Delete the tahun akademik
                $tahun_akademik->delete();
            });

            return redirect()->route('admin.tahun-akademik.index')
                ->with('success', 'Tahun akademik berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('admin.tahun-akademik.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Set tahun akademik as active
     */
    public function setActive(TahunAkademik $tahun_akademik)
    {
        try {
            // Check if already active
            if ($tahun_akademik->is_active) {
                return redirect()->route('admin.tahun-akademik.index')
                    ->with('info', 'Tahun akademik ' . $tahun_akademik->tahun . ' sudah aktif.');
            }

            DB::transaction(function () use ($tahun_akademik) {
                // Deactivate all other tahun akademik
                TahunAkademik::where('id', '!=', $tahun_akademik->id)->update(['is_active' => false]);

                // Activate this one
                $tahun_akademik->update(['is_active' => true]);
            });

            return redirect()->route('admin.tahun-akademik.index')
                ->with('success', 'Tahun akademik ' . $tahun_akademik->tahun . ' berhasil diaktifkan.');

        } catch (\Exception $e) {
            return redirect()->route('admin.tahun-akademik.index')
                ->with('error', 'Terjadi kesalahan saat mengaktifkan tahun akademik: ' . $e->getMessage());
        }
    }

    /**
     * Handle bulk operations
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,deactivate',
            'selected_ids' => 'required|array|min:1',
            'selected_ids.*' => 'exists:tahun_akademik,id'
        ]);

        $action = $request->get('action');
        $selectedIds = $request->get('selected_ids');
        $tahunAkademikList = TahunAkademik::whereIn('id', $selectedIds)->get();

        try {
            DB::transaction(function () use ($action, $tahunAkademikList) {
                switch ($action) {
                    case 'delete':
                        $this->bulkDelete($tahunAkademikList);
                        break;
                    case 'deactivate':
                        $this->bulkDeactivate($tahunAkademikList);
                        break;
                }
            });

            $count = $tahunAkademikList->count();
            $message = $this->getBulkActionMessage($action, $count);

            return redirect()->route('admin.tahun-akademik.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->route('admin.tahun-akademik.index')
                ->with('error', 'Terjadi kesalahan saat melakukan operasi: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete tahun akademik
     */
    private function bulkDelete($tahunAkademikList)
    {
        foreach ($tahunAkademikList as $tahunAkademik) {
            // Check if can be deleted
            if ($tahunAkademik->is_active) {
                throw new \Exception("Tahun akademik aktif ({$tahunAkademik->tahun}) tidak dapat dihapus.");
            }

            if ($tahunAkademik->siswa()->count() > 0) {
                throw new \Exception("Tahun akademik {$tahunAkademik->tahun} tidak dapat dihapus karena masih memiliki data siswa.");
            }

            // Delete related data
            if ($tahunAkademik->selectionProcessStatus) {
                $tahunAkademik->selectionProcessStatus->delete();
            }

            $tahunAkademik->delete();
        }
    }

    /**
     * Bulk deactivate tahun akademik
     */
    private function bulkDeactivate($tahunAkademikList)
    {
        foreach ($tahunAkademikList as $tahunAkademik) {
            $tahunAkademik->update(['is_active' => false]);
        }
    }

    /**
     * Get bulk action success message
     */
    private function getBulkActionMessage($action, $count)
    {
        switch ($action) {
            case 'delete':
                return "{$count} tahun akademik berhasil dihapus.";
            case 'deactivate':
                return "{$count} tahun akademik berhasil dinonaktifkan.";
            default:
                return "Operasi berhasil dilakukan pada {$count} tahun akademik.";
        }
    }
}
