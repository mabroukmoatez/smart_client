# HighLevel API Integration Examples

This document provides detailed examples of how to use the HighLevel (LeadConnector) API for sending WhatsApp messages.

## API Authentication

All API requests require authentication via Private Integration token:

```
Authorization: Bearer YOUR_PRIVATE_INTEGRATION_TOKEN
Version: 2021-07-28
```

## Example: Send WhatsApp Template Message

### Using cURL

```bash
curl --request POST \
  --url https://services.leadconnectorhq.com/conversations/messages/outbound \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer YOUR_PRIVATE_INTEGRATION_TOKEN' \
  --header 'Content-Type: application/json' \
  --header 'Version: 2021-07-28' \
  --data '{
    "type": "WhatsApp",
    "contactId": "CONTACT_ID",
    "templateId": "TEMPLATE_ID",
    "customData": {
      "name": "John Doe"
    }
  }'
```

### Using Laravel HTTP Client

```php
use Illuminate\Support\Facades\Http;

$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . env('HIGHLEVEL_API_TOKEN'),
    'Version' => '2021-07-28',
    'Accept' => 'application/json',
    'Content-Type' => 'application/json',
])
->post('https://services.leadconnectorhq.com/conversations/messages/outbound', [
    'type' => 'WhatsApp',
    'contactId' => 'contact_id_here',
    'templateId' => 'template_id_here',
    'customData' => [
        'name' => 'John Doe',
    ],
]);

$data = $response->json();
```

### Using HighLevelApiService (This App)

```php
use App\Services\HighLevelApiService;

$api = app(HighLevelApiService::class);

$response = $api->sendTemplateMessage(
    phone: '+971501234567',
    templateId: 'your_template_id',
    templateData: [
        'name' => 'John Doe',
    ]
);

// Response
[
    'messageId' => 'msg_xxxxx',
    'conversationId' => 'conv_xxxxx',
    'contactId' => 'contact_xxxxx',
]
```

## Example: Get WhatsApp Templates

### Using cURL

```bash
curl --request GET \
  --url 'https://services.leadconnectorhq.com/conversations/templates?locationId=YOUR_LOCATION_ID' \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer YOUR_PRIVATE_INTEGRATION_TOKEN' \
  --header 'Version: 2021-07-28'
```

### Using HighLevelApiService

```php
$templates = $api->getWhatsAppTemplates();

foreach ($templates as $template) {
    echo "ID: {$template['id']}\n";
    echo "Name: {$template['name']}\n";
    echo "Status: {$template['status']}\n";
    echo "Language: {$template['language']}\n";
}
```

## Example: Create or Get Contact

### Using cURL (Search)

```bash
curl --request GET \
  --url 'https://services.leadconnectorhq.com/contacts/search?locationId=YOUR_LOCATION_ID&phone=%2B971501234567' \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer YOUR_PRIVATE_INTEGRATION_TOKEN' \
  --header 'Version: 2021-07-28'
```

### Using cURL (Create)

```bash
curl --request POST \
  --url https://services.leadconnectorhq.com/contacts \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer YOUR_PRIVATE_INTEGRATION_TOKEN' \
  --header 'Content-Type: application/json' \
  --header 'Version: 2021-07-28' \
  --data '{
    "locationId": "YOUR_LOCATION_ID",
    "phone": "+971501234567",
    "name": "John Doe"
  }'
```

### Using HighLevelApiService

```php
$contact = $api->getOrCreateContact(
    phone: '+971501234567',
    name: 'John Doe'
);

echo "Contact ID: {$contact['id']}\n";
```

## Example: Get Message Status

### Using cURL

```bash
curl --request GET \
  --url https://services.leadconnectorhq.com/conversations/messages/MESSAGE_ID \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer YOUR_PRIVATE_INTEGRATION_TOKEN' \
  --header 'Version: 2021-07-28'
```

### Using HighLevelApiService

```php
$status = $api->getMessageStatus('message_id_here');

echo "Status: {$status['status']}\n";
echo "Delivered: {$status['deliveredAt']}\n";
echo "Read: {$status['readAt']}\n";
```

## WhatsApp Template Variables

When sending template messages, you can include variables:

```php
$response = $api->sendTemplateMessage(
    phone: '+971501234567',
    templateId: 'your_template_id',
    templateData: [
        'name' => 'John Doe',              // {{1}} in template
        'company' => 'Acme Inc',           // {{2}} in template
        'appointment_date' => 'March 15',  // {{3}} in template
        'appointment_time' => '2:00 PM',   // {{4}} in template
    ]
);
```

## Error Handling

```php
use Exception;

try {
    $response = $api->sendTemplateMessage(
        phone: '+971501234567',
        templateId: 'template_id',
        templateData: ['name' => 'John']
    );

    echo "Message sent! ID: {$response['messageId']}";
} catch (Exception $e) {
    // Log error
    Log::error('Failed to send WhatsApp message', [
        'error' => $e->getMessage(),
        'phone' => '+971501234567',
    ]);

    // Handle error
    echo "Error: {$e->getMessage()}";
}
```

## Complete Integration Example

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

// Complete example of sending WhatsApp message
class WhatsAppMessageService
{
    private string $apiUrl = 'https://services.leadconnectorhq.com';
    private string $apiToken;
    private string $locationId;

    public function __construct()
    {
        $this->apiToken = config('services.highlevel.api_token');
        $this->locationId = config('services.highlevel.location_id');
    }

    public function sendMessage(string $phone, string $templateId, array $data = []): array
    {
        // Step 1: Create or get contact
        $contact = $this->getOrCreateContact($phone, $data['name'] ?? null);

        // Step 2: Send template message
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiToken}",
            'Version' => '2021-07-28',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])
        ->post("{$this->apiUrl}/conversations/messages/outbound", [
            'type' => 'WhatsApp',
            'contactId' => $contact['id'],
            'templateId' => $templateId,
            'customData' => $data,
        ]);

        if (!$response->successful()) {
            throw new Exception("API Error: {$response->body()}");
        }

        return $response->json();
    }

    private function getOrCreateContact(string $phone, ?string $name = null): array
    {
        // Search for existing contact
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

        // Create new contact
        $response = Http::withHeaders($this->getHeaders())
            ->post("{$this->apiUrl}/contacts", [
                'locationId' => $this->locationId,
                'phone' => $phone,
                'name' => $name,
            ]);

        if (!$response->successful()) {
            throw new Exception("Failed to create contact: {$response->body()}");
        }

        return $response->json('contact', []);
    }

    private function getHeaders(): array
    {
        return [
            'Authorization' => "Bearer {$this->apiToken}",
            'Version' => '2021-07-28',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }
}

// Usage:
$service = new WhatsAppMessageService();
$result = $service->sendMessage(
    phone: '+971501234567',
    templateId: 'your_template_id',
    data: [
        'name' => 'John Doe',
        'company' => 'Acme Inc',
    ]
);
```

## Response Examples

### Successful Message Send

```json
{
  "messageId": "msg_abc123xyz",
  "conversationId": "conv_def456uvw",
  "contactId": "contact_ghi789rst",
  "status": "pending",
  "createdAt": "2024-01-15T10:30:00.000Z"
}
```

### Error Response

```json
{
  "statusCode": 400,
  "message": "Template not found",
  "error": "Bad Request"
}
```

### Get Templates Response

```json
{
  "templates": [
    {
      "id": "template_123",
      "name": "welcome_message",
      "status": "APPROVED",
      "language": "en",
      "category": "MARKETING",
      "components": [...]
    }
  ]
}
```

## Rate Limits

HighLevel API has rate limits:
- 120 requests per minute per location
- 10,000 requests per day per location

Always implement:
- Rate limiting in your application
- Retry logic with exponential backoff
- Error handling and logging

## Best Practices

1. **Always normalize phone numbers** to E.164 format (+971501234567)
2. **Cache templates** instead of fetching on every request
3. **Implement retry logic** for failed messages
4. **Log all API interactions** for debugging
5. **Use queue jobs** for sending messages asynchronously
6. **Validate template IDs** before sending
7. **Handle API errors gracefully**
8. **Monitor API usage** to avoid rate limits

## References

- HighLevel API Documentation: https://highlevel.stoplight.io/
- Private Integrations Guide: https://help.leadconnectorhq.com/support/solutions/articles/155000002774
