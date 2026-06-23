<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class EventResource extends JsonResource {
  
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array {
    return [
      'id' => Crypt::encryptString((string) $this->id), // Strict ID Encryption
      'title' => $this->title,
      'slug' => $this->slug,
      'description' => $this->description,
      'category' => $this->category,
      'city' => $this->city,
      'start_date' => $this->start_date->toIso8601String(),
      'end_date' => $this->end_date->toIso8601String(),
      'price' => $this->price,
      'total_seats' => $this->total_seats,
      'available_seats' => $this->available_seats,
      'approval_status' => $this->approval_status->value,
      'banner_url' => asset('storage/' . $this->banner_path),
    ];
  }
}