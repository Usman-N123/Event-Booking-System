<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManageEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'price' => ['required', 'numeric', 'min:0'],
        ];

        if (! $isUpdate) {
            $rules['start_date'][] = 'after:now';
            $rules['total_seats'] = ['required', 'integer', 'min:1'];
            $rules['banner'] = ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'];
            $rules['noc_document'] = ['required', 'file', 'mimes:pdf', 'max:5120'];
        } else {
            $rules['banner'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'];
            $rules['noc_document'] = ['nullable', 'file', 'mimes:pdf', 'max:5120'];
        }

        return $rules;
    }
}
