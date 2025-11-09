<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class HighLevelApiService
{
    private string $apiUrl;
    private string $apiToken;
    private string $apiVersion;
    private string $locationId;

    public function __construct()
    {
        $this->apiUrl = config('services.highlevel.api_url');
        $this->apiToken = config('services.highlevel.api_token');
        $this->apiVersion = config('services.highlevel.api_version');
        $this->locationId = config('services.highlevel.location_id');
    }

    /**
     * Get all WhatsApp templates from HighLevel.
     *
     * @return array
     * @throws Exception
     */
    public function getWhatsAppTemplates(): array
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->apiUrl}/conversations/templates", [
                    'locationId' => $this->locationId,
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
     * Send a WhatsApp message using a template.
     *
     * @param string $phone Phone number in E.164 format (e.g., +971501234567)
     * @param string $templateId The template ID from HighLevel
     * @param array $customFields Optional custom fields for template variables
     * @return array API response
     * @throws Exception
     */
    public function sendWhatsAppMessage(string $phone, string $templateId, array $customFields = []): array
    {
        try {
            $payload = [
                'locationId' => $this->locationId,
                'contactPhone' => $phone,
                'templateId' => $templateId,
            ];

            // Add custom fields if provided (for template variables)
            if (!empty($customFields)) {
                $payload['customFields'] = $customFields;
            }

            $response = Http::withHeaders($this->getHeaders())
                ->post("{$this->apiUrl}/conversations/messages", $payload);

            if (!$response->successful()) {
                throw new Exception("Failed to send message: {$response->body()}");
            }

            $data = $response->json();

            Log::info('HighLevel API: Message sent successfully', [
                'phone' => $phone,
                'template_id' => $templateId,
                'message_id' => $data['messageId'] ?? null,
            ]);

            return $data;
        } catch (Exception $e) {
            Log::error('HighLevel API: Failed to send message', [
                'phone' => $phone,
                'template_id' => $templateId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Send a WhatsApp template message (alternative method).
     *
     * This uses the LeadConnector API endpoint for sending template messages.
     *
     * @param string $phone
     * @param string $templateId
     * @param array $templateData Template variables (name, etc.)
     * @return array
     * @throws Exception
     */
    public function sendTemplateMessage(string $phone, string $templateId, array $templateData = []): array
    {
        try {
            // First, create or get contact
            $contact = $this->getOrCreateContact($phone, $templateData['name'] ?? null);

            // Send message to contact
            $payload = [
                'type' => 'WhatsApp',
                'contactId' => $contact['id'],
                'templateId' => $templateId,
                'customData' => $templateData,
            ];

            $response = Http::withHeaders($this->getHeaders())
                ->post("{$this->apiUrl}/conversations/messages/outbound", $payload);

            if (!$response->successful()) {
                throw new Exception("Failed to send template message: {$response->body()}");
            }

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
     * @return array
     * @throws Exception
     */
    public function getOrCreateContact(string $phone, ?string $name = null): array
    {
        try {
            // Try to find existing contact
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->apiUrl}/contacts/search", [
                    'locationId' => $this->locationId,
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
                'locationId' => $this->locationId,
                'phone' => $phone,
            ];

            if ($name) {
                $contactData['name'] = $name;
            }

            $response = Http::withHeaders($this->getHeaders())
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
     * @return array
     * @throws Exception
     */
    public function getMessageStatus(string $messageId): array
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
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
     * Get API request headers.
     *
     * @return array
     */
    private function getHeaders(): array
    {
        return [
            'Authorization' => "Bearer {$this->apiToken}",
            'Version' => $this->apiVersion,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Test API connection.
     *
     * @return bool
     */
    public function testConnection(): bool
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->get("{$this->apiUrl}/locations/{$this->locationId}");

            return $response->successful();
        } catch (Exception $e) {
            Log::error('HighLevel API: Connection test failed', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
