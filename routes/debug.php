<?php

use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;

Route::get('/debug/templates', function () {
    if (!auth()->check()) {
        return 'Please login first';
    }

    $user = auth()->user();

    if (!$user->highlevel_api_token) {
        return 'HighLevel not connected. Go to Settings.';
    }

    $token = Crypt::decryptString($user->highlevel_api_token);
    $locationId = $user->highlevel_location_id;
    $apiUrl = 'https://services.leadconnectorhq.com';

    $headers = [
        'Authorization' => "Bearer {$token}",
        'Version' => '2021-07-28',
        'Accept' => 'application/json',
    ];

    $results = [];

    // Try different endpoints
    $endpoints = [
        "GET /locations/{$locationId}/templates",
        "GET /locations/{$locationId}/templates/whatsapp",
        "GET /conversations/templates?locationId={$locationId}",
        "GET /templates?locationId={$locationId}",
        "GET /conversations/whatsapp/templates?locationId={$locationId}",
        "GET /whatsapp/templates?locationId={$locationId}",
        "GET /locations/{$locationId}/whatsapp/templates",
        "GET /conversations/{$locationId}/templates",
    ];

    foreach ($endpoints as $endpoint) {
        preg_match('/^(GET|POST) (.+)$/', $endpoint, $matches);
        $method = $matches[1];
        $path = $matches[2];

        try {
            $response = Http::withHeaders($headers)->get("{$apiUrl}{$path}");

            $results[$endpoint] = [
                'status' => $response->status(),
                'success' => $response->successful(),
                'body' => $response->json(),
            ];
        } catch (\Exception $e) {
            $results[$endpoint] = [
                'error' => $e->getMessage(),
            ];
        }
    }

    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
})->middleware('auth')->name('debug.templates');
