<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\AutomationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // File Upload routes
    Route::prefix('files')->name('files.')->group(function () {
        Route::get('/', [FileUploadController::class, 'index'])->name('index');
        Route::get('/create', [FileUploadController::class, 'create'])->name('create');
        Route::post('/upload', [FileUploadController::class, 'upload'])->name('upload');
        Route::get('/map-columns', [FileUploadController::class, 'mapColumns'])->name('map-columns');
        Route::post('/store', [FileUploadController::class, 'store'])->name('store');
        Route::get('/{file}/download', [FileUploadController::class, 'download'])->name('download');
        Route::get('/{file}/preview', [FileUploadController::class, 'preview'])->name('preview');
        Route::delete('/{file}', [FileUploadController::class, 'destroy'])->name('destroy');
    });

    // Automation routes
    Route::prefix('automation')->name('automation.')->group(function () {
        Route::get('/', [AutomationController::class, 'index'])->name('index');
        Route::get('/create', [AutomationController::class, 'create'])->name('create');
        Route::post('/calculate-stats', [AutomationController::class, 'calculateStats'])->name('calculate-stats');
        Route::post('/store', [AutomationController::class, 'store'])->name('store');
        Route::get('/{campaign}', [AutomationController::class, 'show'])->name('show');
        Route::post('/{campaign}/cancel', [AutomationController::class, 'cancel'])->name('cancel');
    });
});

require __DIR__.'/auth.php';
