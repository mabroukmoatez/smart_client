# Phone Number Normalization Examples

This document demonstrates how the phone normalization service works with UAE phone numbers.

## Overview

The `PhoneNormalizationService` automatically converts phone numbers to the UAE E.164 format: `+971XXXXXXXXX`

## Normalization Rules

### Rule 1: Numbers starting with "05"
```
Input:  0501234567
Output: +971501234567

Input:  0521234567
Output: +971521234567

Input:  0561234567
Output: +971561234567
```

### Rule 2: Numbers starting with "5" (without leading zero)
```
Input:  501234567
Output: +971501234567

Input:  521234567
Output: +971521234567
```

### Rule 3: Numbers starting with "971" (without +)
```
Input:  971501234567
Output: +971501234567

Input:  971521234567
Output: +971521234567
```

### Rule 4: Already in correct format
```
Input:  +971501234567
Output: +971501234567 (no change)
```

## Code Examples

### Example 1: Normalize Single Phone Number

```php
use App\Services\PhoneNormalizationService;

$phoneService = new PhoneNormalizationService();

// Test various formats
$phones = [
    '0501234567',
    '501234567',
    '971501234567',
    '+971501234567',
    '0521234567',
    '  0501234567  ', // with spaces
    '050-123-4567',   // with dashes
    '050 123 4567',   // with spaces
];

foreach ($phones as $phone) {
    $normalized = $phoneService->normalize($phone);
    echo "Input: {$phone} -> Output: {$normalized}\n";
}
```

Output:
```
Input: 0501234567 -> Output: +971501234567
Input: 501234567 -> Output: +971501234567
Input: 971501234567 -> Output: +971501234567
Input: +971501234567 -> Output: +971501234567
Input: 0521234567 -> Output: +971521234567
Input:   0501234567   -> Output: +971501234567
Input: 050-123-4567 -> Output: +971501234567
Input: 050 123 4567 -> Output: +971501234567
```

### Example 2: Normalize Array of Phone Numbers

```php
$phones = [
    '0501234567',
    '0521234567',
    '0561234567',
    'invalid',
    '971501234567',
    '+971501234567',
];

$normalized = $phoneService->normalizeArray($phones);

print_r($normalized);
```

Output:
```
Array
(
    [0] => +971501234567
    [1] => +971521234567
    [2] => +971561234567
    [3] => +971501234567
    [4] => +971501234567
)
```

Note: Invalid numbers are automatically filtered out.

### Example 3: Validate Phone Numbers

```php
$phones = [
    '0501234567',     // Valid
    '1234567',        // Invalid (too short)
    'abc123',         // Invalid (contains letters)
    '+971501234567',  // Valid
    '0501234',        // Invalid (too short)
];

foreach ($phones as $phone) {
    $isValid = $phoneService->isValid($phone);
    echo "Phone: {$phone} - Valid: " . ($isValid ? 'Yes' : 'No') . "\n";
}
```

Output:
```
Phone: 0501234567 - Valid: Yes
Phone: 1234567 - Valid: No
Phone: abc123 - Valid: No
Phone: +971501234567 - Valid: Yes
Phone: 0501234 - Valid: No
```

### Example 4: Format for Display

```php
$phones = [
    '+971501234567',
    '+971521234567',
    '+971561234567',
];

foreach ($phones as $phone) {
    $formatted = $phoneService->formatForDisplay($phone);
    echo "Phone: {$phone} -> Formatted: {$formatted}\n";
}
```

Output:
```
Phone: +971501234567 -> Formatted: +971 50 123 4567
Phone: +971521234567 -> Formatted: +971 52 123 4567
Phone: +971561234567 -> Formatted: +971 56 123 4567
```

## UAE Phone Number Patterns

### Mobile Numbers
UAE mobile numbers start with these prefixes (after +971):
- **50** (Etisalat)
- **52** (Du)
- **54** (Etisalat)
- **55** (Etisalat)
- **56** (Du)
- **58** (Du)

Format: `+971 5X XXX XXXX` (9 digits after country code)

### Landline Numbers
UAE landline numbers start with these area codes:
- **2** (Abu Dhabi)
- **3** (Al Ain)
- **4** (Dubai)
- **6** (Sharjah, Ajman, Umm Al Quwain)
- **7** (Ras Al Khaimah)
- **9** (Fujairah)

Format: `+971 X XXX XXXX` (7-8 digits after country code)

## CSV File Examples

### Example CSV with Phone Numbers

```csv
Name,Phone,Email
John Doe,0501234567,john@example.com
Jane Smith,501234567,jane@example.com
Bob Johnson,971521234567,bob@example.com
Alice Williams,+971561234567,alice@example.com
Charlie Brown,050-123-4567,charlie@example.com
```

After processing, all phone numbers will be normalized to:
```csv
Name,Phone,Email
John Doe,+971501234567,john@example.com
Jane Smith,+971501234567,jane@example.com
Bob Johnson,+971521234567,bob@example.com
Alice Williams,+971561234567,alice@example.com
Charlie Brown,+971501234567,charlie@example.com
```

## Integration with File Upload

When you upload a file, the system:

1. **Reads the CSV** with column mapping
2. **Normalizes each phone number** automatically
3. **Filters out invalid numbers**
4. **Stores normalized numbers** in the database
5. **Shows validation results** in preview

### Example Integration Code

```php
use App\Services\FileProcessingService;
use App\Services\PhoneNormalizationService;

$fileService = new FileProcessingService();
$phoneService = new PhoneNormalizationService();

// Read CSV with mapping
$data = $fileService->readCsvWithMapping($csvPath, [
    'phone_column' => 'Phone',
    'name_column' => 'Name',
]);

// Normalize and validate
$validRecipients = [];
$invalidRecipients = [];

foreach ($data as $row) {
    $normalized = $phoneService->normalize($row['phone']);

    if ($normalized) {
        $validRecipients[] = [
            'name' => $row['name'],
            'phone' => $normalized,
            'original_phone' => $row['phone'],
        ];
    } else {
        $invalidRecipients[] = [
            'name' => $row['name'],
            'phone' => $row['phone'],
            'reason' => 'Invalid phone number format',
        ];
    }
}

echo "Valid: " . count($validRecipients) . "\n";
echo "Invalid: " . count($invalidRecipients) . "\n";
```

## Testing

### Unit Test Example

```php
namespace Tests\Unit;

use App\Services\PhoneNormalizationService;
use Tests\TestCase;

class PhoneNormalizationTest extends TestCase
{
    private PhoneNormalizationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PhoneNormalizationService();
    }

    public function test_normalizes_phone_with_leading_zero()
    {
        $result = $this->service->normalize('0501234567');
        $this->assertEquals('+971501234567', $result);
    }

    public function test_normalizes_phone_without_leading_zero()
    {
        $result = $this->service->normalize('501234567');
        $this->assertEquals('+971501234567', $result);
    }

    public function test_normalizes_phone_with_971_prefix()
    {
        $result = $this->service->normalize('971501234567');
        $this->assertEquals('+971501234567', $result);
    }

    public function test_keeps_already_normalized_phone()
    {
        $result = $this->service->normalize('+971501234567');
        $this->assertEquals('+971501234567', $result);
    }

    public function test_returns_null_for_invalid_phone()
    {
        $result = $this->service->normalize('invalid');
        $this->assertNull($result);
    }

    public function test_removes_spaces_and_dashes()
    {
        $result = $this->service->normalize('050-123-4567');
        $this->assertEquals('+971501234567', $result);

        $result = $this->service->normalize('050 123 4567');
        $this->assertEquals('+971501234567', $result);
    }
}
```

Run tests:
```bash
php artisan test --filter PhoneNormalizationTest
```

## Common Issues and Solutions

### Issue 1: Numbers with Country Code +971 but Missing Mobile Prefix

```
Input:  +9711234567 (should be +971501234567)
Output: null (invalid)
```

**Solution**: Ensure input has correct mobile prefix (50, 52, 54, 55, 56, 58)

### Issue 2: International Numbers

```
Input:  +1234567890 (US number)
Output: null (not UAE)
```

**Solution**: This service only handles UAE numbers. Filter by country before normalizing.

### Issue 3: Numbers with Extensions

```
Input:  0501234567 ext. 123
Output: +971501234567 (extension removed)
```

**Solution**: Extensions are automatically removed during normalization.

## Best Practices

1. **Always normalize before storing** in database
2. **Validate before sending** WhatsApp messages
3. **Show original and normalized** in preview
4. **Log invalid numbers** for review
5. **Provide feedback** to users about invalid numbers
6. **Handle null returns** gracefully

## Summary

The phone normalization service ensures all UAE phone numbers are in the correct E.164 format (`+971XXXXXXXXX`) before sending WhatsApp messages through the HighLevel API. This improves delivery rates and maintains data consistency.
