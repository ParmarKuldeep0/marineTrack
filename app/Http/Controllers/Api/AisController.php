<?php
// app/Http/Controllers/Api/AisController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\AisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AisController extends Controller
{
    public function store(Request $request, AisService $aisService)
    {
        try {
            // Log incoming request for debugging
            Log::info('AIS position request received', $request->all());
            
            $validated = $request->validate([
                'mmsi' => 'required|string',
                'name' => 'nullable|string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'speed' => 'nullable|numeric',
                'course' => 'nullable|numeric',
                'heading' => 'nullable|numeric',
                'destination' => 'nullable|string',
                'status' => 'nullable|string',
                'eta' => 'nullable|date'
            ]);

            $aisService->processPosition($validated);

            return response()->json([
                'success' => true,
                'message' => 'Position saved successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error saving AIS position: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save position: ' . $e->getMessage()
            ], 500);
        }
    }

    public function active(AisService $aisService)
    {
        try {
            $vessels = $aisService->getActiveVessels();
            return response()->json([
                'success' => true,
                'vessels' => $vessels
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting active vessels: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'vessels' => []
            ], 500);
        }
    }
    public function ports(AisService $aisService)
    {
        try {
            $ports = $aisService->getPorts();
            return response()->json([
                'success' => true,
                'ports' => $ports
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting ports: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'ports' => []
            ], 500);
        }
    }

    public function resolve(Request $request, AisService $aisService)
    {
        $validated = $request->validate([
            'destination' => 'required|string'
        ]);

        $coords = $aisService->findPortCoordinates($validated['destination']);

        return response()->json([
            'success' => true,
            'coords' => $coords
        ]);
    }
}
