<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\AutomationController;
use App\Http\Controllers\SettingsController;
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

    // Settings routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('/credentials', [SettingsController::class, 'storeCredentials'])->name('store-credentials');
        Route::post('/test-connection', [SettingsController::class, 'testConnection'])->name('test-connection');
        Route::delete('/disconnect', [SettingsController::class, 'disconnect'])->name('disconnect');
        Route::get('/account-info', [SettingsController::class, 'getAccountInfo'])->name('account-info');
    });

    // File Upload routes
    Route::prefix('files')->name('files.')->group(function () {
        Route::get('/', [FileUploadController::class, 'index'])->name('index');
        Route::get('/create', [FileUploadController::class, 'create'])->name('create');
        Route::post('/upload', [FileUploadController::class, 'upload'])->name('upload');
        Route::get('/map-columns', [FileUploadController::class, 'mapColumns'])->name('map-columns');
        Route::post('/store', [FileUploadController::class, 'store'])->name('store');
        Route::post('/merge', [FileUploadController::class, 'merge'])->name('merge');
        Route::post('/bulk-delete', [FileUploadController::class, 'bulkDelete'])->name('bulk-delete');
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

// Debug routes (remove in production)
if (app()->environment('local')) {
    require __DIR__.'/debug.php';
}
