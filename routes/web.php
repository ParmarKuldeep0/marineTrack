<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\Api\AisController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/track', [App\Http\Controllers\TrackingController::class, 'index'])
    ->name('track');

Route::get('/about', function (){
    return view('components.pages.about');
})->name('about');

Route::get(('/fleet'), function(){
    return view('components.pages.fleet');
})->name('fleet');

Route::prefix('api')->group(function(){
    Route::post('positions', [AisController::class, 'store']);
    Route::get('vessels/active', [AisController::class, 'active']);
});

Route::get('/ais-token', function () {
    return response()->json([
        'key' => config('services.ais.api_key')
    ]);
});

Route::get('ports', [App\Http\Controllers\Api\AisController::class, 'ports']);

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');