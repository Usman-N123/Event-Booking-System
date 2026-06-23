<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Features\Dashboard\GetGlobalStatisticsFeature;
use App\Repositories\Contracts\EventRepositoryInterface;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected GetGlobalStatisticsFeature $getGlobalStatisticsFeature,
        protected EventRepositoryInterface $eventRepository,
        protected BookingRepositoryInterface $bookingRepository
    ) {}

    public function attendee(): View
    {
        $bookings = $this->bookingRepository->getUserBookings(Auth::id());
        
        return view('dashboard.attendee', compact('bookings'));
    }

    public function organizer(): View
    {
        $myEvents = $this->eventRepository->getOrganizerEvents(Auth::id());
        $organizerStats = $this->bookingRepository->getOrganizerStats(Auth::id());

        return view('dashboard.organizer', compact('myEvents', 'organizerStats'));
    }

    public function admin(): View
    {
        $globalStats = $this->getGlobalStatisticsFeature->handle();
        $pendingEvents = $this->eventRepository->getPendingEvents();
        $bookings = $this->bookingRepository->getAllBookings();
        $allEvents = $this->eventRepository->getAllEvents();
        
        return view('dashboard.admin', compact('globalStats', 'pendingEvents', 'bookings', 'allEvents'));
    }
}