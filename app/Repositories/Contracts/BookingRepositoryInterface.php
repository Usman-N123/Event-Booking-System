<?php

namespace App\Repositories\Contracts;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Collection;

interface BookingRepositoryInterface
{
    /**
     * Create a new booking record.
     *
     * @param array $data
     * @return Booking
     */
    public function create(array $data): Booking;

    /**
     * Get all bookings for a specific attendee, with event relationship eager-loaded.
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserBookings(int $userId): Collection;

    /**
     * Get the total number of bookings.
     *
     * @return int
     */
    public function getTotalCount(): int;

    /**
     * Get the total revenue from confirmed bookings.
     *
     * @return float
     */
    public function getTotalRevenue(): float;

    /**
     * Get all bookings.
     *
     * @return Collection
     */
    public function getAllBookings(): Collection;

    /**
     * Get aggregated stats (total bookings, total revenue) for an organizer's events.
     *
     * @param int $organizerId
     * @return array{total_bookings: int, total_revenue: float}
     */
    public function getOrganizerStats(int $organizerId): array;
}