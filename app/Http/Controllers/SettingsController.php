<?php

namespace App\Http\Controllers;

use App\Services\HighLevelApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Exception;

class SettingsController extends Controller
{
    public function __construct(
        private HighLevelApiService $highLevelApi
    ) {}

    /**
     * Display settings page.
     */
    public function index()
    {
        $user = auth()->user();

        return view('settings.index', [
            'hasCredentials' => !empty($user->highlevel_api_token),
            'isConnected' => $user->highlevel_connected,
            'connectedAt' => $user->highlevel_connected_at,
            'locationId' => $user->highlevel_location_id,
        ]);
    }

    /**
     * Store HighLevel API credentials.
     */
    public function storeCredentials(Request $request)
    {
        $request->validate([
            'api_token' => 'required|string',
            'location_id' => 'required|string',
        ]);

        try {
            $user = auth()->user();

            // Encrypt and store credentials
            $user->update([
                'highlevel_api_token' => Crypt::encryptString($request->api_token),
                'highlevel_location_id' => $request->location_id,
                'highlevel_connected' => false,
                'highlevel_connected_at' => null,
            ]);

            return redirect()->route('settings.index')
                ->with('success', 'API credentials saved successfully. Please test the connection.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to save credentials: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Test HighLevel API connection.
     */
    public function testConnection()
    {
        try {
            $user = auth()->user();

            if (empty($user->highlevel_api_token)) {
                return back()->withErrors(['error' => 'Please configure your API credentials first.']);
            }

            // Decrypt token
            $apiToken = Crypt::decryptString($user->highlevel_api_token);

            // Test connection
            $result = $this->highLevelApi->testConnection($apiToken, $user->highlevel_location_id);

            if ($result['success']) {
                // Update connection status
                $user->update([
                    'highlevel_connected' => true,
                    'highlevel_connected_at' => now(),
                ]);

                return redirect()->route('settings.index')
                    ->with('success', 'Connection successful! You can now use WhatsApp automation.');
            } else {
                return back()->withErrors(['error' => 'Connection failed: ' . $result['message']]);
            }
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Connection test failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Disconnect HighLevel account.
     */
    public function disconnect()
    {
        try {
            $user = auth()->user();

            $user->update([
                'highlevel_api_token' => null,
                'highlevel_location_id' => null,
                'highlevel_connected' => false,
                'highlevel_connected_at' => null,
            ]);

            return redirect()->route('settings.index')
                ->with('success', 'HighLevel account disconnected successfully.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to disconnect: ' . $e->getMessage()]);
        }
    }

    /**
     * Get account information from HighLevel.
     */
    public function getAccountInfo()
    {
        try {
            $user = auth()->user();

            if (empty($user->highlevel_api_token)) {
                return response()->json(['error' => 'Not connected'], 400);
            }

            $apiToken = Crypt::decryptString($user->highlevel_api_token);
            $info = $this->highLevelApi->getLocationInfo($apiToken, $user->highlevel_location_id);

            return response()->json([
                'success' => true,
                'data' => $info,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
