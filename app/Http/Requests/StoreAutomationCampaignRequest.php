<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAutomationCampaignRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'file_ids' => ['required', 'array', 'min:1'],
            'file_ids.*' => ['required', 'exists:uploaded_files,id'],
            'template_id' => ['required', 'string', 'max:255'],
            'template_name' => ['required', 'string', 'max:255'],
            'scheduled_at' => ['required', 'date', 'after:now'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Campaign name is required.',
            'file_ids.required' => 'Please select at least one file.',
            'file_ids.min' => 'Please select at least one file.',
            'template_id.required' => 'Please select a WhatsApp template.',
            'scheduled_at.required' => 'Please schedule a date and time for the campaign.',
            'scheduled_at.after' => 'Scheduled time must be in the future.',
        ];
    }
}
