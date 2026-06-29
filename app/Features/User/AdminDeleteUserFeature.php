<?php

namespace App\Features\User;

use App\Features\Dashboard\GetGlobalStatisticsFeature;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Exception;

class AdminDeleteUserFeature
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /**
     * Soft-delete a user account. The user's related records
     * (bookings, events) remain intact via foreign keys.
     *
     * @param int $userId
     * @return bool
     * @throws Exception
     */
    public function handle(int $userId): bool
    {
        $result = $this->userRepository->softDelete($userId);

        if (! $result) {
            throw new Exception('User not found or could not be deleted.', 404);
        }

        Cache::forget(GetGlobalStatisticsFeature::CACHE_KEY);

        return true;
    }
}
