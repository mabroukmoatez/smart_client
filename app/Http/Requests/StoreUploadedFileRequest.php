<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUploadedFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $maxUploadSize = config('app.max_upload_size', 10240); // KB

        return [
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                "max:{$maxUploadSize}",
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        $maxUploadSizeMB = config('app.max_upload_size', 10240) / 1024;

        return [
            'file.required' => 'Please select a file to upload.',
            'file.mimes' => 'File must be in XLSX, XLS, or CSV format.',
            'file.max' => "File size must not exceed {$maxUploadSizeMB}MB.",
        ];
    }
}
