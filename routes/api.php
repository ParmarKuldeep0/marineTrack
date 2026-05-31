<?php

use App\Service\AisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AisController;

Route::post('/ais/store', function (Request $request) {
    app(AisService::class)->processPosition([
        'mmsi' => $request->mmsi,
        'name' => $request->name,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        'speed' => $request->speed ?? 0,
        'course' => $request->course ?? 0,
        'heading' => $request->heading ?? 0,
        'destination' => $request->destination,
    ]);
    
    return response()->json(['success' => true]);
});

Route::post('/ais/positions', [AisController::class, 'store']);
Route::post('/positions', [AisController::class, 'store']);
Route::get('/ais/active', [AisController::class, 'active']);
Route::get('/vessels/active', [AisController::class, 'active']);
Route::get('/ports', [AisController::class, 'ports']);
Route::get('/ports/resolve', [AisController::class, 'resolve']);