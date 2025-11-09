<?php

namespace App\Http\Controllers;

use App\Models\UploadedFile;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        // Get file statistics
        $stats = [
            'total_files' => $user->uploadedFiles()->count(),
            'total_contacts' => $user->uploadedFiles()->sum('row_count'),
            'highlevel_connected' => (bool) $user->highlevel_connected,
            'external_api_connected' => (bool) $user->external_api_connected,
        ];

        // Recent files
        $recentFiles = $user->uploadedFiles()
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recentFiles'));
    }
}
