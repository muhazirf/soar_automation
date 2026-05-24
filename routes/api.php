<?php

use App\Http\Controllers\Api\ChartController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Chart data endpoints
Route::prefix('charts')->middleware(['auth'])->group(function () {
    // Alert trends data
    Route::get('/alerts/trends', [ChartController::class, 'alertTrends']);

    // Threat statistics
    Route::get('/threats/stats', [ChartController::class, 'threatStats']);

    // Incident trends
    Route::get('/incidents/trends', [ChartController::class, 'incidentTrends']);

    // System metrics
    Route::get('/system/metrics', [ChartController::class, 'systemMetrics']);

    // User clearance distribution
    Route::get('/users/clearance', [ChartController::class, 'clearanceDistribution']);

    // Real-time data
    Route::get('/realtime/{type}', [ChartController::class, 'realtime'])
        ->where('type', 'alerts|incidents|threats|cpu|memory');
});

// Health check endpoint (no auth required)
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'version' => config('app.version', '1.0.0'),
    ]);
});
