<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
          'id' => Crypt::encryptString((string) $this->id),
          'event_id' => Crypt::encryptString((string) $this->event_id),
          'quantity' => $this->quantity,
          'total_amount' => $this->total_amount,
          'booking_reference' => $this->booking_reference,
          'status' => $this->status->value,
          'booked_at' => $this->created_at->toIso8601String(),
        ];
    }
}