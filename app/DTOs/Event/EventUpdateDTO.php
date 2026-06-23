<?php

namespace App\DTOs\Event;

use App\Http\Requests\UpdateEventRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

class EventUpdateDTO
{
  public function __construct(
    public readonly int $eventId,
    public readonly string $title,
    public readonly string $description,
    public readonly string $category,
    public readonly string $city,
    public readonly Carbon $startDate,
    public readonly Carbon $endDate,
    public readonly float $price,
    public readonly ?UploadedFile $banner = null,
    public readonly ?UploadedFile $nocDocument = null,
  ) {}

  public static function fromRequest(UpdateEventRequest $request, int $eventId): self
  {
    return new self(
      eventId: $eventId,
      title: $request->validated('title'),
      description: $request->validated('description'),
      category: $request->validated('category'),
      city: $request->validated('city'),
      startDate: Carbon::parse($request->validated('start_date')),
      endDate: Carbon::parse($request->validated('end_date')),
      price: (float) $request->validated('price'),
      banner: $request->hasFile('banner') ? $request->file('banner') : null,
      nocDocument: $request->hasFile('noc_document') ? $request->file('noc_document') : null,
    );
  }
}
