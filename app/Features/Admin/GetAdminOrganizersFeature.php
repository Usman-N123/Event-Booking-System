<?php

namespace App\Features\Admin;

use App\DTOs\Admin\AdminOrganizerFilterDTO;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class GetAdminOrganizersFeature
{
    public const CACHE_TTL = 900;

    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function handle(AdminOrganizerFilterDTO $dto): LengthAwarePaginator
    {
        $cacheKey = $dto->generateCacheKey();

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($dto) {
            return $this->userRepository->getFilteredOrganizersPaginated($dto);
        });
    }
}
