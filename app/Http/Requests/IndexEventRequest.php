<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Public route, everyone is allowed
    }

    public function rules(): array
    {
        return [
          'search' => ['nullable', 'string', 'max:100'],
          'category' => ['nullable', 'string', 'max:100'],
          'city' => ['nullable', 'string', 'max:100'],
          'date_from' => ['nullable', 'date'],
          'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
          'sort_price' => ['nullable', 'in:asc,desc'],
          'per_page' => ['nullable', 'integer', 'min:1', 'max:100'], // Prevent someone requesting 1,000,000 records
        ];
    }
}