<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Exception;

class HighLevelApiService
{
    private string $apiUrl = 'https://services.leadconnectorhq.com';
    private string $apiVersion = '2021-07-28';

    /**
     * Get all WhatsApp templates from HighLevel.
     *
     * @param string|null $apiToken
     * @param string|null $locationId
     * @return array
     * @throws Exception
     */
    public function getWhatsAppTemplates(?string $apiToken = null, ?string $locationId = null): array
    {
        $credentials = $this->getCredentials($apiToken, $locationId);

        try {
            $response = Http::withHeaders($this->getHeaders($credentials['token']))
                ->get("{$this->apiUrl}/conversations/templates", [
                    'locationId' => $credentials['locationId'],
                ]);

            if (!$response->successful()) {
                throw new Exception("Failed to fetch templates: {$response->body()}");
            }

            return $response->json('templates', []);
        } catch (Exception $e) {
            Log::error('HighLevel API: Failed to get templates', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Send a WhatsApp template message.
     *
     * @param string $phone
     * @param string $templateId
     * @param array $templateData
     * @param string|null $apiToken
     * @param string|null $locationId
     * @return array
     * @throws Exception
     */
    public function sendTemplateMessage(
        string $phone,
        string $templateId,
        array $templateData = [],
        ?string $apiToken = null,
        ?string $locationId = null
    ): array {
        $credentials = $this->getCredentials($apiToken, $locationId);

        try {
            // First, create or get contact
            $contact = $this->getOrCreateContact($phone, $templateData['name'] ?? null, $credentials['token'], $credentials['locationId']);

            // Send message to contact
            $payload = [
                'type' => 'WhatsApp',
                'contactId' => $contact['id'],
                'templateId' => $templateId,
                'customData' => $templateData,
            ];

            $response = Http::withHeaders($this->getHeaders($credentials['token']))
                ->post("{$this->apiUrl}/conversations/messages/outbound", $payload);

            if (!$response->successful()) {
                throw new Exception("Failed to send template message: {$response->body()}");
            }

            Log::info('HighLevel API: Message sent successfully', [
                'phone' => $phone,
                'template_id' => $templateId,
                'contact_id' => $contact['id'],
            ]);

            return $response->json();
        } catch (Exception $e) {
            Log::error('HighLevel API: Failed to send template message', [
                'phone' => $phone,
                'template_id' => $templateId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get or create a contact in HighLevel.
     *
     * @param string $phone
     * @param string|null $name
     * @param string|null $apiToken
     * @param string|null $locationId
     * @return array
     * @throws Exception
     */
    public function getOrCreateContact(
        string $phone,
        ?string $name = null,
        ?string $apiToken = null,
        ?string $locationId = null
    ): array {
        $credentials = $this->getCredentials($apiToken, $locationId);

        try {
            // Try to find existing contact
            $response = Http::withHeaders($this->getHeaders($credentials['token']))
                ->get("{$this->apiUrl}/contacts/search", [
                    'locationId' => $credentials['locationId'],
                    'phone' => $phone,
                ]);

            if ($response->successful()) {
                $contacts = $response->json('contacts', []);
                if (!empty($contacts)) {
                    return $contacts[0];
                }
            }

            // Create new contact if not found
            $contactData = [
                'locationId' => $credentials['locationId'],
                'phone' => $phone,
            ];

            if ($name) {
                $contactData['name'] = $name;
            }

            $response = Http::withHeaders($this->getHeaders($credentials['token']))
                ->post("{$this->apiUrl}/contacts", $contactData);

            if (!$response->successful()) {
                throw new Exception("Failed to create contact: {$response->body()}");
            }

            return $response->json('contact', []);
        } catch (Exception $e) {
            Log::error('HighLevel API: Failed to get/create contact', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get message status from HighLevel.
     *
     * @param string $messageId
     * @param string|null $apiToken
     * @return array
     * @throws Exception
     */
    public function getMessageStatus(string $messageId, ?string $apiToken = null): array
    {
        $credentials = $this->getCredentials($apiToken, null);

        try {
            $response = Http::withHeaders($this->getHeaders($credentials['token']))
                ->get("{$this->apiUrl}/conversations/messages/{$messageId}");

            if (!$response->successful()) {
                throw new Exception("Failed to get message status: {$response->body()}");
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('HighLevel API: Failed to get message status', [
                'message_id' => $messageId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Test API connection.
     *
     * @param string $apiToken
     * @param string $locationId
     * @return array
     */
    public function testConnection(string $apiToken, string $locationId): array
    {
        try {
            $response = Http::withHeaders($this->getHeaders($apiToken))
                ->timeout(10)
                ->get("{$this->apiUrl}/locations/{$locationId}");

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'message' => 'Connection successful',
                    'location' => $data['location'] ?? $data,
                ];
            } else {
                return [
                    'success' => false,
                    'message' => "HTTP {$response->status()}: {$response->body()}",
                ];
            }
        } catch (Exception $e) {
            Log::error('HighLevel API: Connection test failed', [
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get location information.
     *
     * @param string $apiToken
     * @param string $locationId
     * @return array
     * @throws Exception
     */
    public function getLocationInfo(string $apiToken, string $locationId): array
    {
        try {
            $response = Http::withHeaders($this->getHeaders($apiToken))
                ->get("{$this->apiUrl}/locations/{$locationId}");

            if (!$response->successful()) {
                throw new Exception("Failed to get location info: {$response->body()}");
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('HighLevel API: Failed to get location info', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get API request headers.
     *
     * @param string $apiToken
     * @return array
     */
    private function getHeaders(string $apiToken): array
    {
        return [
            'Authorization' => "Bearer {$apiToken}",
            'Version' => $this->apiVersion,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Get credentials from user or fallback to config.
     *
     * @param string|null $apiToken
     * @param string|null $locationId
     * @return array
     * @throws Exception
     */
    private function getCredentials(?string $apiToken = null, ?string $locationId = null): array
    {
        // If credentials provided, use them
        if ($apiToken && $locationId) {
            return [
                'token' => $apiToken,
                'locationId' => $locationId,
            ];
        }

        // Try to get from authenticated user
        if (auth()->check()) {
            $user = auth()->user();

            if ($user->highlevel_api_token && $user->highlevel_location_id) {
                return [
                    'token' => Crypt::decryptString($user->highlevel_api_token),
                    'locationId' => $user->highlevel_location_id,
                ];
            }
        }

        // Fallback to config (for backward compatibility)
        $configToken = config('services.highlevel.api_token');
        $configLocation = config('services.highlevel.location_id');

        if ($configToken && $configLocation) {
            return [
                'token' => $configToken,
                'locationId' => $configLocation,
            ];
        }

        throw new Exception('HighLevel API credentials not configured. Please add your credentials in Settings.');
    }
}
