<?php

namespace App\Features\Admin;

use App\DTOs\Admin\AdminUserFilterDTO;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class GetAdminUsersFeature
{
    public const CACHE_TTL = 900;

    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function handle(AdminUserFilterDTO $dto): LengthAwarePaginator
    {
        $cacheKey = $dto->generateCacheKey();

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($dto) {
            return $this->userRepository->getFilteredUsersPaginated($dto);
        });
    }
}
