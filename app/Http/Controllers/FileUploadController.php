<?php

namespace App\Http\Controllers;

use App\Models\UploadedFile;
use App\Services\FileProcessingService;
use App\Services\PhoneNormalizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class FileUploadController extends Controller
{
    public function __construct(
        private FileProcessingService $fileProcessor,
        private PhoneNormalizationService $phoneNormalizer
    ) {}

    /**
     * Display upload page.
     */
    public function index()
    {
        $files = auth()->user()->uploadedFiles()
            ->latest()
            ->paginate(10);

        return view('files.index', compact('files'));
    }

    /**
     * Show upload form.
     */
    public function create()
    {
        return view('files.upload');
    }

    /**
     * Handle file upload and return column headers for mapping.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:' . config('app.max_upload_size', 10240),
            ],
        ]);

        try {
            $file = $request->file('file');
            $userId = auth()->id();

            // Process file
            $result = $this->fileProcessor->processUploadedFile($file, $userId);

            // Validate row count
            $maxRows = config('app.max_csv_rows', 50000);
            if ($result['row_count'] > $maxRows) {
                // Clean up
                Storage::delete($result['original_path']);
                Storage::delete($result['csv_path']);

                return back()->withErrors([
                    'file' => "File has too many rows ({$result['row_count']}). Maximum allowed: {$maxRows}",
                ]);
            }

            // Store temporary data in session for column mapping
            session([
                'upload_temp' => [
                    'original_filename' => $file->getClientOriginalName(),
                    'original_path' => $result['original_path'],
                    'csv_path' => $result['csv_path'],
                    'headers' => $result['headers'],
                    'row_count' => $result['row_count'],
                    'mime_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ],
            ]);

            return redirect()->route('files.map-columns');
        } catch (Exception $e) {
            return back()->withErrors(['file' => $e->getMessage()]);
        }
    }

    /**
     * Show column mapping form.
     */
    public function mapColumns()
    {
        $uploadData = session('upload_temp');

        if (!$uploadData) {
            return redirect()->route('files.create')
                ->withErrors(['error' => 'No file uploaded. Please upload a file first.']);
        }

        return view('files.map-columns', [
            'headers' => $uploadData['headers'],
            'filename' => $uploadData['original_filename'],
            'rowCount' => $uploadData['row_count'],
        ]);
    }

    /**
     * Save file with column mapping.
     */
    public function store(Request $request)
    {
        $request->validate([
            'phone_column' => 'required|string',
            'name_column' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
        ]);

        $uploadData = session('upload_temp');

        if (!$uploadData) {
            return redirect()->route('files.create')
                ->withErrors(['error' => 'Session expired. Please upload the file again.']);
        }

        try {
            // Create uploaded file record
            $uploadedFile = UploadedFile::create([
                'user_id' => auth()->id(),
                'original_filename' => $uploadData['original_filename'],
                'original_file_path' => $uploadData['original_path'],
                'original_mime_type' => $uploadData['mime_type'],
                'original_file_size' => $uploadData['file_size'],
                'converted_csv_path' => $uploadData['csv_path'],
                'row_count' => $uploadData['row_count'],
                'column_mapping' => [
                    'phone_column' => $request->phone_column,
                    'name_column' => $request->name_column,
                ],
                'notes' => $request->notes,
            ]);

            // Clear session
            session()->forget('upload_temp');

            return redirect()->route('files.index')
                ->with('success', 'File uploaded and processed successfully!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Download converted CSV file.
     */
    public function download(UploadedFile $file)
    {
        // Check authorization
        if ($file->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $path = Storage::path($file->converted_csv_path);

        if (!file_exists($path)) {
            abort(404, 'File not found.');
        }

        return response()->download(
            $path,
            'converted_' . $file->original_filename . '.csv'
        );
    }

    /**
     * Preview file data.
     */
    public function preview(UploadedFile $file)
    {
        // Check authorization
        if ($file->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Read first 10 rows with mapping
        $data = $this->fileProcessor->readCsvWithMapping(
            $file->converted_csv_path,
            $file->column_mapping
        );

        // Normalize phones for preview
        $preview = array_slice($data, 0, 10);
        foreach ($preview as &$row) {
            $row['normalized_phone'] = $this->phoneNormalizer->normalize($row['phone']);
            $row['is_valid'] = $row['normalized_phone'] !== null;
        }

        return view('files.preview', [
            'file' => $file,
            'preview' => $preview,
        ]);
    }

    /**
     * Delete uploaded file.
     */
    public function destroy(UploadedFile $file)
    {
        // Check authorization
        if ($file->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Delete physical files
            $this->fileProcessor->deleteFiles(
                $file->original_file_path,
                $file->converted_csv_path
            );

            // Delete record
            $file->delete();

            return redirect()->route('files.index')
                ->with('success', 'File deleted successfully!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
