<?php

namespace App\Services;

class PhoneNormalizationService
{
    /**
     * Normalize a phone number to UAE format (+971).
     *
     * Rules:
     * - If starts with "05", convert to "+9715" (e.g., 0501234567 -> +971501234567)
     * - If starts with "5" and no country code, add "+971" (e.g., 501234567 -> +971501234567)
     * - If starts with "971" without +, add "+" (e.g., 971501234567 -> +971501234567)
     * - If already has +971, keep as is
     * - Remove spaces, dashes, and parentheses
     *
     * @param string $phone
     * @return string|null Normalized phone number or null if invalid
     */
    public function normalize(string $phone): ?string
    {
        // Remove all non-digit characters except +
        $phone = preg_replace('/[^\d+]/', '', $phone);

        // Remove leading zeros but keep track of them
        $phone = ltrim($phone, '0');

        // If empty after cleaning, return null
        if (empty($phone)) {
            return null;
        }

        // Already has +971
        if (str_starts_with($phone, '+971')) {
            return $this->validateAndReturn($phone);
        }

        // Has 971 without +
        if (str_starts_with($phone, '971')) {
            return $this->validateAndReturn('+' . $phone);
        }

        // UAE mobile numbers start with 5 (after country code)
        // Handle cases like "0501234567" or "501234567"
        if (str_starts_with($phone, '5')) {
            return $this->validateAndReturn('+971' . $phone);
        }

        // If the original had leading zeros (like 0501234567)
        // After ltrim, we'd have "501234567", which is handled above

        // For numbers that don't match patterns, try adding +971
        // This handles edge cases
        if (strlen($phone) === 9 && ctype_digit($phone)) {
            return $this->validateAndReturn('+971' . $phone);
        }

        // Return null if we can't normalize
        return null;
    }

    /**
     * Normalize an array of phone numbers.
     *
     * @param array $phones
     * @return array Array of normalized phone numbers (invalid ones are filtered out)
     */
    public function normalizeArray(array $phones): array
    {
        return array_filter(
            array_map(fn($phone) => $this->normalize($phone), $phones)
        );
    }

    /**
     * Validate and return the normalized phone number.
     *
     * @param string $phone
     * @return string|null
     */
    private function validateAndReturn(string $phone): ?string
    {
        // UAE mobile numbers should be +971 followed by 9 digits starting with 5
        // Format: +971XXXXXXXXX (total 13 characters)
        if (preg_match('/^\+971[5][0-9]{8}$/', $phone)) {
            return $phone;
        }

        // Also accept landline numbers (starting with 2, 3, 4, 6, 7, 9)
        // Format: +971XXXXXXXXX
        if (preg_match('/^\+971[2-9][0-9]{7,8}$/', $phone)) {
            return $phone;
        }

        return null;
    }

    /**
     * Check if a phone number is valid UAE format.
     *
     * @param string $phone
     * @return bool
     */
    public function isValid(string $phone): bool
    {
        return $this->normalize($phone) !== null;
    }

    /**
     * Format phone number for display.
     *
     * @param string $phone
     * @return string
     */
    public function formatForDisplay(string $phone): string
    {
        $normalized = $this->normalize($phone);

        if (!$normalized) {
            return $phone;
        }

        // Format as +971 50 123 4567
        if (preg_match('/^\+971([5][0-9]{8})$/', $normalized, $matches)) {
            $number = $matches[1];
            return '+971 ' . substr($number, 0, 2) . ' ' . substr($number, 2, 3) . ' ' . substr($number, 5);
        }

        return $normalized;
    }
}
