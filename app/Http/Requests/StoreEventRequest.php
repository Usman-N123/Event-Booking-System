<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required','string','max:255'],
            'description' => ['required', 'string'],
            'category' => ['required','string','max:100'],
            'city' => ['required','string','max:100'],
            'start_date' => ['required','date','after:now'],
            'end_date' => ['required','date','after:start_date'],
            'price' => ['required', 'numeric','min:0'],
            'total_seats' => ['required', 'integer', 'min:1'],

            'banner' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'noc_document' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            
        ];
    }
}
