<?php

namespace App\DTOs\Booking;

use App\Http\Requests\StoreBookingRequest;
use Illuminate\Http\UploadedFile;

class BookingCreateDTO
{
  public function __construct(
    public readonly int $eventId,
    public readonly int $attendeeId,
    public readonly int $quantity,
    public readonly ?UploadedFile $passPicture = null,
  ) {}

  public static function fromRequest(StoreBookingRequest $request): self
  {
    return new self(
      eventId: (int) $request->validated('event_id'),
      attendeeId: auth()->id(),
      quantity: (int) $request->validated('quantity'),
      passPicture: $request->hasFile('pass_picture') ? $request->file('pass_picture') : null,
    );
  }
}