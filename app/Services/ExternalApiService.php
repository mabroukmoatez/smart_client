<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class ExternalApiService
{
    /**
     * Test connection to external API.
     *
     * @param string $apiUrl
     * @param string $apiToken
     * @return array
     */
    public function testConnection(string $apiUrl, string $apiToken): array
    {
        try {
            $response = Http::withHeaders($this->getHeaders($apiToken))
                ->timeout(10)
                ->get($apiUrl);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Connection successful',
                    'status_code' => $response->status(),
                ];
            } else {
                return [
                    'success' => false,
                    'message' => "HTTP {$response->status()}: {$response->body()}",
                ];
            }
        } catch (Exception $e) {
            Log::error('External API: Connection test failed', [
                'url' => $apiUrl,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Fetch clients from external API.
     *
     * @param string $apiUrl
     * @param string $apiToken
     * @return array
     * @throws Exception
     */
    public function fetchClients(string $apiUrl, string $apiToken): array
    {
        try {
            $response = Http::withHeaders($this->getHeaders($apiToken))
                ->timeout(30)
                ->get($apiUrl);

            if (!$response->successful()) {
                throw new Exception("Failed to fetch clients: HTTP {$response->status()} - {$response->body()}");
            }

            $data = $response->json();

            Log::info('External API: Clients fetched', [
                'url' => $apiUrl,
                'response_structure' => array_keys($data),
            ]);

            // Try to extract clients array from different possible response structures
            $clients = $this->extractClientsFromResponse($data);

            if (empty($clients)) {
                throw new Exception('No clients found in API response. Response structure: ' . json_encode(array_keys($data)));
            }

            return $clients;
        } catch (Exception $e) {
            Log::error('External API: Failed to fetch clients', [
                'url' => $apiUrl,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Extract clients array from response.
     *
     * @param array $response
     * @return array
     */
    private function extractClientsFromResponse(array $response): array
    {
        // Handle different response structures
        if (isset($response['clients'])) {
            return $response['clients'];
        } elseif (isset($response['data'])) {
            return is_array($response['data']) ? $response['data'] : [];
        } elseif (isset($response['contacts'])) {
            return $response['contacts'];
        } elseif (isset($response['customers'])) {
            return $response['customers'];
        } elseif (isset($response['users'])) {
            return $response['users'];
        } elseif (is_array($response) && !empty($response) && isset($response[0])) {
            // Response itself is an array of clients
            return $response;
        }

        return [];
    }

    /**
     * Normalize external client data to our format.
     *
     * @param array $client
     * @return array|null Returns null if required fields are missing
     */
    public function normalizeClientData(array $client): ?array
    {
        // Try to find phone number in different possible keys
        $phone = $client['phone'] ?? $client['mobile'] ?? $client['telephone'] ?? $client['phone_number'] ?? $client['tel'] ?? null;

        // Skip if no phone number
        if (!$phone) {
            return null;
        }

        // Try to find name in different possible keys
        $name = $client['name'] ?? $client['full_name'] ?? $client['fullname'] ?? null;

        // If no single name field, try combining first and last name
        if (!$name) {
            $firstName = $client['first_name'] ?? $client['firstname'] ?? '';
            $lastName = $client['last_name'] ?? $client['lastname'] ?? '';
            $name = trim($firstName . ' ' . $lastName);
        }

        // Extract email if available
        $email = $client['email'] ?? $client['email_address'] ?? null;

        return [
            'phone' => $phone,
            'name' => $name ?: null,
            'email' => $email,
            'raw_data' => $client, // Store original data for reference
        ];
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
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }
}
