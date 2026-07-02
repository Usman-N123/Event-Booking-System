<?php

namespace App\DTOs\Admin;

use App\Http\Requests\Admin\AdminUserFilterRequest;
use Illuminate\Support\Facades\Cache;

class AdminUserFilterDTO
{
    public function __construct(
        public readonly ?string $search,
        public readonly ?string $role,
        public readonly ?string $dateFrom,
        public readonly ?string $dateTo,
        public readonly int $perPage,
    ) {}

    public static function fromRequest(AdminUserFilterRequest $request): self
    {
        return new self(
            search:   $request->validated('search'),
            role:     $request->validated('role'),
            dateFrom: $request->validated('date_from'),
            dateTo:   $request->validated('date_to'),
            perPage:  15,
        );
    }

    public function generateCacheKey(): string
    {
        $version = Cache::get('admin.users.version', 1);

        $hash = md5(json_encode([
            $this->search,
            $this->role,
            $this->dateFrom,
            $this->dateTo,
            $this->perPage,
            request('users_page', 1),
        ]));

        return "admin.users.v{$version}.{$hash}";
    }

    public static function bustListingsCache(): void
    {
        $version = Cache::get('admin.users.version', 1);
        Cache::put('admin.users.version', $version + 1, now()->addYear());
    }
}
