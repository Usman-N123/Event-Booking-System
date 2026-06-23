<?php

namespace App\Features\Dashboard;

use App\Repositories\Contracts\EventRepositoryInterface;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class GetGlobalStatisticsFeature
{
  public const CACHE_KEY = 'admin.dashboard.global_statistics';
  public const CACHE_DURATION_SECONDS = 3600;

  public function __construct(
    protected EventRepositoryInterface $eventRepository,
    protected BookingRepositoryInterface $bookingRepository,
    protected UserRepositoryInterface $userRepository
  ) {}

  /**
   * @return array<string, mixed>
   */
  public function handle(): array
  {
    return Cache::remember(self::CACHE_KEY, self::CACHE_DURATION_SECONDS, function (): array {
      return [
        'total_events' => $this->eventRepository->getTotalCount(),
        'pending_approvals' => $this->eventRepository->getPendingCount(),
        'total_bookings' => $this->bookingRepository->getTotalCount(),
        'total_revenue' => $this->bookingRepository->getTotalRevenue(),
        'total_users' => $this->userRepository->getTotalCount(),
        'total_organizers' => $this->userRepository->getOrganizerCount(),
        'last_updated' => now()->toIso8601String(),
      ];
    });
  }
}