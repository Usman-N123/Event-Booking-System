<?php

namespace App\DTOs\Event;

use App\Http\Requests\IndexEventRequest;
use Illuminate\Support\Facades\Cache;

class EventFilterDTO
{
    public function __construct(
        public readonly ?string $search,
        public readonly ?string $category,
        public readonly ?string $city,
        public readonly ?string $dateFrom,
        public readonly ?string $dateTo,
        public readonly ?string $sortPrice,
        public readonly int $perPage,
    ) {}

    public static function fromRequest(IndexEventRequest $request): self
    {
        return new self(
            search: $request->validated('search'),
            category: $request->validated('category'),
            city: $request->validated('city'),
            dateFrom: $request->validated('date_from'),
            dateTo: $request->validated('date_to'),
            sortPrice: $request->validated('sort_price'),
            perPage: (int) $request->validated('per_page', 15),
        );
    }

    /**
     * Generate a unique cache key based on the current filter state.
     */
    public function generateCacheKey(): string
    {
        $version = Cache::get('events.public.version', 1);

        $hash = md5(json_encode([
            $this->search, $this->category, $this->city,
            $this->dateFrom, $this->dateTo, $this->sortPrice, $this->perPage,
            request('page', 1),
        ]));

        return "events.public.v{$version}.{$hash}";
    }

    public static function bustListingsCache(): void
    {
        $version = Cache::get('events.public.version', 1);
        Cache::put('events.public.version', $version + 1, now()->addYear());
    }
}