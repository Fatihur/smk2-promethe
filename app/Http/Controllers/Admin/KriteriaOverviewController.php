<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\MasterKriteria;

class KriteriaOverviewController extends Controller
{
    /**
     * Display overview of all kriteria across jurusan
     */
    public function index()
    {
        // Get all jurusan with their kriteria
        $jurusans = Jurusan::with(['kriteriaJurusan.masterKriteria'])
                          ->whereHas('kriteriaJurusan')
                          ->orderBy('kode_jurusan')
                          ->get();

        // Get all master kriteria
        $masterKriterias = MasterKriteria::orderBy('kode_kriteria')->get();

        // Create matrix data for easy display
        $matrix = [];
        foreach ($jurusans as $jurusan) {
            $matrix[$jurusan->id] = [
                'jurusan' => $jurusan,
                'kriteria' => []
            ];

            foreach ($jurusan->kriteriaJurusan as $kj) {
                $matrix[$jurusan->id]['kriteria'][$kj->master_kriteria_id] = $kj;
            }
        }

        return view('admin.kriteria-overview.index', compact('matrix', 'masterKriterias', 'jurusans'));
    }

    /**
     * Export kriteria data to various formats
     */
    public function export($format = 'json')
    {
        $data = [];

        $jurusans = Jurusan::with(['kriteriaJurusan.masterKriteria'])
                          ->whereHas('kriteriaJurusan')
                          ->orderBy('kode_jurusan')
                          ->get();

        foreach ($jurusans as $jurusan) {
            $kriteriaData = [];
            foreach ($jurusan->kriteriaJurusan as $kj) {
                $kriteriaData[] = [
                    'kriteria' => $kj->masterKriteria->nama_kriteria,
                    'kode' => $kj->masterKriteria->kode_kriteria,
                    'nilai_min' => $kj->nilai_min,
                    'nilai_max' => $kj->nilai_max,
                    'rentang' => $kj->nilai_min == $kj->nilai_max ? $kj->nilai_min : "{$kj->nilai_min}-{$kj->nilai_max}",
                    'is_active' => $kj->is_active
                ];
            }

            $data[] = [
                'jurusan' => $jurusan->nama_jurusan,
                'kode_jurusan' => $jurusan->kode_jurusan,
                'kriteria' => $kriteriaData
            ];
        }

        switch ($format) {
            case 'json':
                return response()->json($data, 200, [], JSON_PRETTY_PRINT);

            case 'csv':
                $filename = 'kriteria_overview_' . date('Y-m-d') . '.csv';
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => "attachment; filename=\"$filename\"",
                ];

                $callback = function() use ($data) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, ['Jurusan', 'Kode Jurusan', 'Kriteria', 'Kode Kriteria', 'Nilai Min', 'Nilai Max', 'Rentang', 'Status']);

                    foreach ($data as $jurusan) {
                        foreach ($jurusan['kriteria'] as $kriteria) {
                            fputcsv($file, [
                                $jurusan['jurusan'],
                                $jurusan['kode_jurusan'],
                                $kriteria['kriteria'],
                                $kriteria['kode'],
                                $kriteria['nilai_min'],
                                $kriteria['nilai_max'],
                                $kriteria['rentang'],
                                $kriteria['is_active'] ? 'Aktif' : 'Tidak Aktif'
                            ]);
                        }
                    }
                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);

            default:
                return response()->json(['error' => 'Format not supported'], 400);
        }
    }
}
