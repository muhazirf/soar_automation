<?php

use App\Http\Controllers\AlertController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\PlaybookController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThreatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VirusTotalController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Alerts
Route::prefix('alerts')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [AlertController::class, 'index'])->name('alerts.index');
    Route::get('/{id}', [AlertController::class, 'show'])->name('alerts.show');
    Route::post('/{id}/status', [AlertController::class, 'updateStatus'])->name('alerts.status');
    Route::post('/{id}/escalate', [AlertController::class, 'escalate'])->name('alerts.escalate');
});

// Incidents
Route::prefix('incidents')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [IncidentController::class, 'index'])->name('incidents.index');
    Route::get('/create', [IncidentController::class, 'create'])->name('incidents.create');
    Route::post('/', [IncidentController::class, 'store'])->name('incidents.store');
    Route::get('/{id}', [IncidentController::class, 'show'])->name('incidents.show');
    Route::get('/{id}/edit', [IncidentController::class, 'edit'])->name('incidents.edit');
    Route::put('/{id}', [IncidentController::class, 'update'])->name('incidents.update');
    Route::delete('/{id}', [IncidentController::class, 'destroy'])->name('incidents.destroy');
});

// Playbooks
Route::prefix('playbooks')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [PlaybookController::class, 'index'])->name('playbooks.index');
    Route::get('/{id}', [PlaybookController::class, 'show'])->name('playbooks.show');
    Route::post('/{id}/execute', [PlaybookController::class, 'execute'])->name('playbooks.execute');
});

// Threat Intelligence
Route::prefix('threats')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [ThreatController::class, 'index'])->name('threats.index');
    Route::get('/{id}', [ThreatController::class, 'show'])->name('threats.show');
});

// VirusTotal
Route::prefix('virustotal')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [VirusTotalController::class, 'index'])->name('virustotal.index');
    Route::get('/{id}', [VirusTotalController::class, 'show'])->name('virustotal.show');
    Route::post('/scan', [VirusTotalController::class, 'scan'])->name('virustotal.scan');
    Route::post('/{id}/rescan', [VirusTotalController::class, 'rescan'])->name('virustotal.rescan');
});

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Users
Route::prefix('users')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Settings
Route::get('/settings', [DashboardController::class, 'settings'])->name('settings')->middleware(['auth', 'verified']);
Route::put('/settings', [DashboardController::class, 'updateSettings'])->name('settings.update')->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
