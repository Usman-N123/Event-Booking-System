<?php

namespace App\DTOs\Event;

use App\Http\Requests\StoreEventRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

class EventCreateDTO
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
        public readonly int $totalSeats,
        public readonly UploadedFile $banner,
        public readonly UploadedFile $nocDocument,
    ) {}

    /**
     * Factory method to create DTO from the Form Request.
     */
    public static function fromRequest(StoreEventRequest $request): self
    {
        return new self(
            organizerId: auth()->id(), // Automatically assign the logged-in user
            title: $request->validated('title'),
            description: $request->validated('description'),
            category: $request->validated('category'),
            city: $request->validated('city'),
            startDate: Carbon::parse($request->validated('start_date')),
            endDate: Carbon::parse($request->validated('end_date')),
            price: (float) $request->validated('price'),
            totalSeats: (int) $request->validated('total_seats'),
            banner: $request->file('banner'),
            nocDocument: $request->file('noc_document'),
        );
    }
}