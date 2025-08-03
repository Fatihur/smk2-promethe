<?php

namespace App\Http\Controllers;

use App\Services\StatusTrackingService;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    protected $statusService;

    public function __construct(StatusTrackingService $statusService)
    {
        $this->statusService = $statusService;
    }

    /**
     * Show comprehensive status dashboard
     */
    public function dashboard()
    {
        $stats = $this->statusService->getDashboardStats();
        return view('status.dashboard', compact('stats'));
    }

    /**
     * Get real-time status data for AJAX requests
     */
    public function getStatusData(Request $request)
    {
        $tahunAkademikId = $request->input('tahun_akademik_id');
        $stats = $this->statusService->getDashboardStats($tahunAkademikId);

        return response()->json($stats);
    }
}
