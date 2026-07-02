<?php

namespace App\DTOs\Admin;

use App\Http\Requests\Admin\AdminEventFilterRequest;
use Illuminate\Support\Facades\Cache;

class AdminEventFilterDTO
{
    public function __construct(
        public readonly ?string $search,
        public readonly ?string $status,
        public readonly ?string $dateFrom,
        public readonly ?string $dateTo,
        public readonly int $perPage,
    ) {}

    public static function fromRequest(AdminEventFilterRequest $request): self
    {
        return new self(
            search:   $request->validated('search'),
            status:   $request->validated('status'),
            dateFrom: $request->validated('date_from'),
            dateTo:   $request->validated('date_to'),
            perPage:  15,
        );
    }

    public function generateCacheKey(): string
    {
        $version = Cache::get('admin.events.version', 1);

        $hash = md5(json_encode([
            $this->search,
            $this->status,
            $this->dateFrom,
            $this->dateTo,
            $this->perPage,
            request('events_page', 1),
        ]));

        return "admin.events.v{$version}.{$hash}";
    }

    public static function bustListingsCache(): void
    {
        $version = Cache::get('admin.events.version', 1);
        Cache::put('admin.events.version', $version + 1, now()->addYear());
    }
}
