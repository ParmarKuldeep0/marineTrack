<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vessel;
use App\Models\VesselPosition;

class DashboardController extends Controller
{
    public function index()
    {
        $perPage = request('per_page', 10); // Get per_page from URL, default 10
        
        return view('dashboard', [
            'totalVessels' => Vessel::count(),
            'activeVessels' => VesselPosition::where('received_at', '>=', now()->subHour())
                ->distinct('vessel_id')->count(),
            'newLastHour' => Vessel::where('created_at', '>=', now()->subHour())->count(),
            'totalPositions' => VesselPosition::count(),
            'avgSpeed' => round(VesselPosition::where('received_at', '>=', now()->subHour())
                ->avg('speed') ?? 0, 1),
            'recentVessels' => Vessel::with('latestPosition')
                ->latest()
                ->paginate($perPage) // 🔥 CHANGE get() to paginate()
                ->appends(request()->query()), // Keep other query params
            'typeStats' => Vessel::selectRaw('type, count(*) as count')
                ->groupBy('type')->orderByDesc('count')->get(),
        ]);
    }
}