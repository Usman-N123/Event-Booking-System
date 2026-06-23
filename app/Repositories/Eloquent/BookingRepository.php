<?php

namespace App\Repositories\Eloquent;

use App\Models\Booking;
use App\Enums\BookingStatus;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class BookingRepository implements BookingRepositoryInterface
{
  /**
   * Create a new booking record.
   *
   * @param array $data
   * @return Booking
   */
  public function create(array $data): Booking
  {
    return Booking::create($data);
  }

  /**
   * Get all bookings for a specific attendee, with event relationship eager-loaded.
   *
   * @param int $userId
   * @return Collection
   */
  public function getUserBookings(int $userId): Collection
  {
    return Booking::with('event')
      ->where('attendee_id', $userId)
      ->latest()
      ->get();
  }

  /**
   * Get the total number of bookings.
   *
   * @return int
   */
  public function getTotalCount(): int
  {
    return Booking::count();
  }

  /**
   * Get the total revenue from confirmed bookings.
   *
   * @return float
   */
  public function getTotalRevenue(): float
  {
    return (float) Booking::where('status', BookingStatus::CONFIRMED->value)->sum('total_amount');
  }

  /**
   * Get all bookings.
   *
   * @return Collection
   */
  public function getAllBookings(): Collection
  {
    return Booking::with(['event', 'attendee'])->latest()->get();
  }

  /**
   * Get aggregated stats for an organizer's events.
   *
   * @param int $organizerId
   * @return array{total_bookings: int, total_revenue: float}
   */
  public function getOrganizerStats(int $organizerId): array
  {
    $stats = Booking::join('events', 'bookings.event_id', '=', 'events.id')
      ->where('events.organizer_id', $organizerId)
      ->whereNull('bookings.deleted_at')
      ->selectRaw('COUNT(bookings.id) as total_bookings, COALESCE(SUM(bookings.total_amount), 0) as total_revenue')
      ->first();

    return [
      'total_bookings' => (int) ($stats->total_bookings ?? 0),
      'total_revenue' => (float) ($stats->total_revenue ?? 0),
    ];
  }
}