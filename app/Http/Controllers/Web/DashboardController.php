<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUserFilterRequest;
use App\Http\Requests\Admin\AdminEventFilterRequest;
use App\Http\Requests\Admin\AdminOrganizerFilterRequest;
use App\DTOs\Admin\AdminUserFilterDTO;
use App\DTOs\Admin\AdminEventFilterDTO;
use App\DTOs\Admin\AdminOrganizerFilterDTO;
use App\Features\Admin\GetAdminUsersFeature;
use App\Features\Admin\GetAdminEventsFeature;
use App\Features\Admin\GetAdminOrganizersFeature;
use App\Features\Dashboard\GetGlobalStatisticsFeature;
use App\Repositories\Contracts\EventRepositoryInterface;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected GetGlobalStatisticsFeature  $getGlobalStatisticsFeature,
        protected GetAdminUsersFeature        $getAdminUsersFeature,
        protected GetAdminEventsFeature       $getAdminEventsFeature,
        protected GetAdminOrganizersFeature   $getAdminOrganizersFeature,
        protected EventRepositoryInterface    $eventRepository,
        protected BookingRepositoryInterface  $bookingRepository,
    ) {}

    public function attendee(): View
    {
        $bookings = $this->bookingRepository->getUserBookings(Auth::id());

        return view('dashboard.attendee', compact('bookings'));
    }

    public function organizer(): View
    {
        $myEvents       = $this->eventRepository->getOrganizerEvents(Auth::id());
        $organizerStats = $this->bookingRepository->getOrganizerStats(Auth::id());

        return view('dashboard.organizer', compact('myEvents', 'organizerStats'));
    }

    public function admin(
        AdminUserFilterRequest      $userRequest,
        AdminEventFilterRequest     $eventRequest,
        AdminOrganizerFilterRequest $organizerRequest
    ): View {
        $globalStats       = $this->getGlobalStatisticsFeature->handle();
        $pendingEvents     = $this->eventRepository->getPendingEvents();
        $bookings          = $this->bookingRepository->getAllBookings();

        $userDTO           = AdminUserFilterDTO::fromRequest($userRequest);
        $eventDTO          = AdminEventFilterDTO::fromRequest($eventRequest);
        $organizerDTO      = AdminOrganizerFilterDTO::fromRequest($organizerRequest);

        $allUsers          = $this->getAdminUsersFeature->handle($userDTO);
        $allEvents         = $this->getAdminEventsFeature->handle($eventDTO);
        $pendingOrganizers = $this->getAdminOrganizersFeature->handle($organizerDTO);

        return view('dashboard.admin', compact(
            'globalStats',
            'pendingEvents',
            'bookings',
            'allUsers',
            'allEvents',
            'pendingOrganizers',
        ));
    }
}