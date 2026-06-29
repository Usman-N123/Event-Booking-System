<?php

namespace App\DTOs\Event;

use App\Http\Requests\ManageEventRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

class ManageEventDTO
{
    public function __construct(
        public readonly int $organizerId,
        public readonly string $title,
        public readonly string $description,
        public readonly string $category,
        public readonly string $city,
        public readonly Carbon $startDate,
        public readonly Carbon $endDate,
        public readonly float $price,
        public readonly ?int $id = null,
        public readonly ?int $totalSeats = null,
        public readonly ?UploadedFile $banner = null,
        public readonly ?UploadedFile $nocDocument = null,
    ) {}

    public static function fromRequest(ManageEventRequest $request, ?int $eventId = null): self
    {
        return new self(
            organizerId: auth()->id(),
            title: $request->validated('title'),
            description: $request->validated('description'),
            category: $request->validated('category'),
            city: $request->validated('city'),
            startDate: Carbon::parse($request->validated('start_date')),
            endDate: Carbon::parse($request->validated('end_date')),
            price: (float) $request->validated('price'),
            id: $eventId,
            totalSeats: $request->has('total_seats') ? (int) $request->validated('total_seats') : null,
            banner: $request->hasFile('banner') ? $request->file('banner') : null,
            nocDocument: $request->hasFile('noc_document') ? $request->file('noc_document') : null,
        );
    }
}
