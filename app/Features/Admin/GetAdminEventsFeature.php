<?php

namespace App\Features\Admin;

use App\DTOs\Admin\AdminEventFilterDTO;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class GetAdminEventsFeature
{
    public const CACHE_TTL = 900;

    public function __construct(
        protected EventRepositoryInterface $eventRepository
    ) {}

    public function handle(AdminEventFilterDTO $dto): LengthAwarePaginator
    {
        $cacheKey = $dto->generateCacheKey();

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($dto) {
            return $this->eventRepository->getAdminFilteredEvents($dto);
        });
    }
}
