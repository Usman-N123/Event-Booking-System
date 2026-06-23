<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
  public function authorize(): bool
  {
    return auth()->check();
  }

  public function rules(): array
  {
    return [
      'title' => ['required', 'string', 'max:255'],
      'description' => ['required', 'string'],
      'category' => ['required', 'string', 'max:100'],
      'city' => ['required', 'string', 'max:100'],
      'start_date' => ['required', 'date'],
      'end_date' => ['required', 'date', 'after:start_date'],
      'price' => ['required', 'numeric', 'min:0'],
      'banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
      'noc_document' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
    ];
  }
}
