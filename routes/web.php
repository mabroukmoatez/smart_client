<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileUploadController;
// use App\Http\Controllers\AutomationController; // Disabled - replaced by Contact Import
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ExternalApiImportController;
use App\Http\Controllers\ContactImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // TEMPORARY: Debug route to check credentials
    Route::get('/debug-credentials', function () {
        $user = auth()->user();
        return response()->json([
            'user_id' => $user->id,
            'has_token' => !empty($user->highlevel_api_token),
            'token_length' => $user->highlevel_api_token ? strlen($user->highlevel_api_token) : 0,
            'location_id' => $user->highlevel_location_id,
            'connected' => $user->highlevel_connected,
            'connected_at' => $user->highlevel_connected_at,
            'note' => 'Visit /settings and click "Test Connection" to verify your API credentials'
        ]);
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Settings routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');

        // HighLevel settings
        Route::post('/credentials', [SettingsController::class, 'storeCredentials'])->name('store-credentials');
        Route::post('/test-connection', [SettingsController::class, 'testConnection'])->name('test-connection');
        Route::delete('/disconnect', [SettingsController::class, 'disconnect'])->name('disconnect');
        Route::get('/account-info', [SettingsController::class, 'getAccountInfo'])->name('account-info');

        // External API settings
        Route::post('/external-api/credentials', [SettingsController::class, 'storeExternalApiCredentials'])->name('store-external-api-credentials');
        Route::post('/external-api/test-connection', [SettingsController::class, 'testExternalApiConnection'])->name('test-external-api-connection');
        Route::delete('/external-api/disconnect', [SettingsController::class, 'disconnectExternalApi'])->name('disconnect-external-api');
    });

    // External API Import routes
    Route::prefix('external-api')->name('external-api.')->group(function () {
        Route::get('/import', [ExternalApiImportController::class, 'index'])->name('index');
        Route::post('/preview', [ExternalApiImportController::class, 'preview'])->name('preview');
        Route::get('/confirm', [ExternalApiImportController::class, 'confirm'])->name('confirm');
        Route::post('/import', [ExternalApiImportController::class, 'import'])->name('import');
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

    // Contact Import routes
    Route::prefix('contact-import')->name('contact-import.')->group(function () {
        Route::get('/', [ContactImportController::class, 'index'])->name('index');
        Route::post('/', [ContactImportController::class, 'store'])->name('store');
        Route::get('/list', [ContactImportController::class, 'list'])->name('list');
        Route::get('/{importJob}', [ContactImportController::class, 'show'])->name('show');
        Route::post('/{importJob}/cancel', [ContactImportController::class, 'cancel'])->name('cancel');
    });

    // Automation routes - DISABLED (replaced by Contact Import system)
    // Route::prefix('automation')->name('automation.')->group(function () {
    //     Route::get('/', [AutomationController::class, 'index'])->name('index');
    //     Route::get('/create', [AutomationController::class, 'create'])->name('create');
    //     Route::post('/calculate-stats', [AutomationController::class, 'calculateStats'])->name('calculate-stats');
    //     Route::post('/store', [AutomationController::class, 'store'])->name('store');
    //     Route::get('/{campaign}', [AutomationController::class, 'show'])->name('show');
    //     Route::post('/{campaign}/cancel', [AutomationController::class, 'cancel'])->name('cancel');
    // });
});

require __DIR__.'/auth.php';
