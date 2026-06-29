<?php

namespace App\DTOs\Booking;

use App\Http\Requests\ManageBookingRequest;
use Illuminate\Http\UploadedFile;

class ManageBookingDTO
{
  public function __construct(
    public readonly int $quantity,
    public readonly ?int $eventId = null,
    public readonly ?int $attendeeId = null,
    public readonly ?int $id = null,
    public readonly ?UploadedFile $passPicture = null,
  ) {}

  public static function fromRequest(ManageBookingRequest $request, ?int $bookingId = null): self
  {
    return new self(
      quantity: (int) $request->validated('quantity'),
      eventId: $request->has('event_id') ? (int) $request->validated('event_id') : null,
      attendeeId: auth()->id(),
      id: $bookingId,
      passPicture: $request->hasFile('pass_picture') ? $request->file('pass_picture') : null,
    );
  }
}
