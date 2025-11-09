<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Exception;

class FileProcessingService
{
    /**
     * Process uploaded file and convert to CSV.
     *
     * @param UploadedFile $file
     * @param int $userId
     * @return array [original_path, csv_path, headers, row_count]
     * @throws Exception
     */
    public function processUploadedFile(UploadedFile $file, int $userId): array
    {
        // Store original file
        $originalPath = $file->store("uploads/{$userId}/originals", 'local');

        // Get file extension
        $extension = strtolower($file->getClientOriginalExtension());

        // If already CSV, just copy it
        if ($extension === 'csv') {
            $csvPath = $this->copyAsCsv($originalPath, $userId);
            $headers = $this->getCsvHeaders($csvPath);
            $rowCount = $this->countCsvRows($csvPath);

            return [
                'original_path' => $originalPath,
                'csv_path' => $csvPath,
                'headers' => $headers,
                'row_count' => $rowCount,
            ];
        }

        // Convert Excel to CSV
        if (in_array($extension, ['xlsx', 'xls'])) {
            $csvPath = $this->convertExcelToCsv($originalPath, $userId);
            $headers = $this->getCsvHeaders($csvPath);
            $rowCount = $this->countCsvRows($csvPath);

            return [
                'original_path' => $originalPath,
                'csv_path' => $csvPath,
                'headers' => $headers,
                'row_count' => $rowCount,
            ];
        }

        throw new Exception('Unsupported file format. Please upload .xlsx, .xls, or .csv files.');
    }

    /**
     * Convert Excel file to CSV.
     *
     * @param string $excelPath
     * @param int $userId
     * @return string CSV file path
     * @throws Exception
     */
    private function convertExcelToCsv(string $excelPath, int $userId): string
    {
        $csvFileName = 'converted_' . time() . '.csv';
        $csvPath = "uploads/{$userId}/converted/{$csvFileName}";

        try {
            // Read Excel and write to CSV
            $data = Excel::toArray([], Storage::path($excelPath));

            if (empty($data) || empty($data[0])) {
                throw new Exception('Excel file is empty');
            }

            // Get first sheet
            $sheet = $data[0];

            // Write to CSV
            $csvContent = $this->arrayToCsv($sheet);
            Storage::put($csvPath, $csvContent);

            return $csvPath;
        } catch (Exception $e) {
            throw new Exception("Failed to convert Excel to CSV: {$e->getMessage()}");
        }
    }

    /**
     * Copy CSV file to converted directory.
     *
     * @param string $originalPath
     * @param int $userId
     * @return string
     */
    private function copyAsCsv(string $originalPath, int $userId): string
    {
        $csvFileName = 'converted_' . time() . '.csv';
        $csvPath = "uploads/{$userId}/converted/{$csvFileName}";

        Storage::copy($originalPath, $csvPath);

        return $csvPath;
    }

    /**
     * Get CSV headers.
     *
     * @param string $csvPath
     * @return array
     */
    public function getCsvHeaders(string $csvPath): array
    {
        $fullPath = Storage::path($csvPath);

        if (!file_exists($fullPath)) {
            return [];
        }

        $handle = fopen($fullPath, 'r');
        $headers = fgetcsv($handle);
        fclose($handle);

        return $headers ?: [];
    }

    /**
     * Count CSV rows (excluding header).
     *
     * @param string $csvPath
     * @return int
     */
    public function countCsvRows(string $csvPath): int
    {
        $fullPath = Storage::path($csvPath);

        if (!file_exists($fullPath)) {
            return 0;
        }

        $lines = 0;
        $handle = fopen($fullPath, 'r');

        // Skip header
        fgets($handle);

        while (!feof($handle)) {
            $line = fgets($handle);
            if (!empty(trim($line))) {
                $lines++;
            }
        }

        fclose($handle);

        return $lines;
    }

    /**
     * Read CSV data with column mapping.
     *
     * @param string $csvPath
     * @param array $columnMapping ['phone_column' => 'Phone', 'name_column' => 'Name']
     * @return array Array of ['phone' => ..., 'name' => ...]
     */
    public function readCsvWithMapping(string $csvPath, array $columnMapping): array
    {
        $fullPath = Storage::path($csvPath);
        $data = [];

        if (!file_exists($fullPath)) {
            return $data;
        }

        $handle = fopen($fullPath, 'r');

        // Read headers
        $headers = fgetcsv($handle);

        // Find column indexes
        $phoneIndex = array_search($columnMapping['phone_column'], $headers);
        $nameIndex = isset($columnMapping['name_column'])
            ? array_search($columnMapping['name_column'], $headers)
            : false;

        // Read rows
        while (($row = fgetcsv($handle)) !== false) {
            if (empty($row) || !isset($row[$phoneIndex])) {
                continue;
            }

            $data[] = [
                'phone' => $row[$phoneIndex],
                'name' => $nameIndex !== false && isset($row[$nameIndex]) ? $row[$nameIndex] : null,
            ];
        }

        fclose($handle);

        return $data;
    }

    /**
     * Convert array to CSV string.
     *
     * @param array $data
     * @return string
     */
    private function arrayToCsv(array $data): string
    {
        $handle = fopen('php://temp', 'r+');

        foreach ($data as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return $csv;
    }

    /**
     * Delete file and its converted version.
     *
     * @param string $originalPath
     * @param string $csvPath
     * @return void
     */
    public function deleteFiles(string $originalPath, string $csvPath): void
    {
        Storage::delete($originalPath);
        Storage::delete($csvPath);
    }
}
