<?php

namespace App\Features\User;

use App\Features\Dashboard\GetGlobalStatisticsFeature;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Exception;

class RejectOrganizerFeature
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /**
     * Reject an organizer account (set is_approved = false).
     * Does NOT delete the account — the organizer remains but cannot publish events.
     *
     * @param int $userId
     * @return bool
     * @throws Exception
     */
    public function handle(int $userId): bool
    {
        $result = $this->userRepository->reject($userId);

        if (! $result) {
            throw new Exception('User not found or could not be rejected.', 404);
        }

        Cache::forget(GetGlobalStatisticsFeature::CACHE_KEY);

        return true;
    }
}
