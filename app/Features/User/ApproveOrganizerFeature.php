<?php

namespace App\Features\User;

use App\DTOs\Admin\AdminOrganizerFilterDTO;
use App\DTOs\Admin\AdminUserFilterDTO;
use App\Features\Dashboard\GetGlobalStatisticsFeature;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Exception;

class ApproveOrganizerFeature
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /**
     * Approve an organizer account (set is_approved = true).
     *
     * @param int $userId
     * @return bool
     * @throws Exception
     */
    public function handle(int $userId): bool
    {
        $result = $this->userRepository->approve($userId);

        if (! $result) {
            throw new Exception('User not found or could not be approved.', 404);
        }

        Cache::forget(GetGlobalStatisticsFeature::CACHE_KEY);
        AdminOrganizerFilterDTO::bustListingsCache();
        AdminUserFilterDTO::bustListingsCache();

        return true;
    }
}
