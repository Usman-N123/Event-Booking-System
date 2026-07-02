<?php

namespace App\DTOs\Admin;

use App\Http\Requests\Admin\AdminOrganizerFilterRequest;
use Illuminate\Support\Facades\Cache;

class AdminOrganizerFilterDTO
{
    public function __construct(
        public readonly ?string $search,
        public readonly ?string $dateFrom,
        public readonly ?string $dateTo,
        public readonly int $perPage,
    ) {}

    public static function fromRequest(AdminOrganizerFilterRequest $request): self
    {
        return new self(
            search:   $request->validated('search'),
            dateFrom: $request->validated('date_from'),
            dateTo:   $request->validated('date_to'),
            perPage:  15,
        );
    }

    public function generateCacheKey(): string
    {
        $version = Cache::get('admin.organizers.version', 1);

        $hash = md5(json_encode([
            $this->search,
            $this->dateFrom,
            $this->dateTo,
            $this->perPage,
            request('organizers_page', 1),
        ]));

        return "admin.organizers.v{$version}.{$hash}";
    }

    public static function bustListingsCache(): void
    {
        $version = Cache::get('admin.organizers.version', 1);
        Cache::put('admin.organizers.version', $version + 1, now()->addYear());
    }
}
