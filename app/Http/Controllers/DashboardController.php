<?php

namespace App\Http\Controllers;

use App\Models\UploadedFile;
use App\Models\AutomationCampaign;
use App\Models\MessageLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        // Get statistics
        $stats = [
            'total_files' => $user->uploadedFiles()->count(),
            'total_campaigns' => $user->automationCampaigns()->count(),
            'total_messages_sent' => MessageLog::whereHas('campaign', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('status', 'sent')->count(),
            'active_campaigns' => $user->automationCampaigns()
                ->whereIn('status', ['scheduled', 'processing'])
                ->count(),
        ];

        // Recent files
        $recentFiles = $user->uploadedFiles()
            ->latest()
            ->limit(5)
            ->get();

        // Recent campaigns
        $recentCampaigns = $user->automationCampaigns()
            ->latest()
            ->limit(5)
            ->get();

        // Campaign status breakdown
        $campaignsByStatus = $user->automationCampaigns()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('dashboard', compact(
            'stats',
            'recentFiles',
            'recentCampaigns',
            'campaignsByStatus'
        ));
    }
}
