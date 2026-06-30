<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManageEventRequest;
use App\DTOs\Event\ManageEventDTO;
use App\Features\Event\ManageEventFeature;
use App\Http\Requests\IndexEventRequest;
use App\DTOs\Event\EventFilterDTO;
use App\Models\Event;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Features\Event\OrganizerCancelEventFeature;
use Exception;

class EventController extends Controller
{
  public function __construct(
    protected ManageEventFeature $manageEventFeature,
    protected EventRepositoryInterface $eventRepository,
    protected OrganizerCancelEventFeature $organizerCancelEventFeature,
  ) {}

  public function index(IndexEventRequest $request): View|RedirectResponse
  {
    try {
      $dto = EventFilterDTO::fromRequest($request);
      $events = $this->eventRepository->getPublicEvents($dto);

      return view('events.index', compact('events'));
    } catch (Exception $e) {
      Log::error('Event Fetching Failed: ' . $e->getMessage());

      return redirect()->route('home')
        ->with('error', 'An error occurred while fetching events.');
    }
  }

  public function show(Event $event): View
  {
    return view('events.show', compact('event'));
  }

  public function store(ManageEventRequest $request): RedirectResponse
  {
    try {
      $dto = ManageEventDTO::fromRequest($request);
      $this->manageEventFeature->handle($dto);

      return redirect()->route('organizer.dashboard')
        ->with('success', 'Event created successfully and is pending admin approval.');
    } catch (Exception $e) {
      Log::error('Event Creation Failed: ' . $e->getMessage());

      return back()->withInput()
        ->with('error', 'An error occurred while creating the event.');
    }
  }

  public function edit(Event $event): View|RedirectResponse
  {
    if ($event->organizer_id !== auth()->id()) {
      abort(403, 'You do not own this event.');
    }

    return view('events.edit', compact('event'));
  }

  /**
   * Update an existing event.
   *
   * @param ManageEventRequest $request
   * @param Event $event
   * @return RedirectResponse
   */
  public function update(ManageEventRequest $request, Event $event): RedirectResponse
  {
    if ($event->organizer_id !== auth()->id()) {
      abort(403, 'You do not own this event.');
    }

    try {
      $dto = ManageEventDTO::fromRequest($request, $event->id);
      $this->manageEventFeature->handle($dto);

      return redirect()->route('organizer.dashboard')
        ->with('success', 'Event updated successfully.');
    } catch (Exception $e) {
      Log::error('Event Update Failed: ' . $e->getMessage());

      return back()->withInput()
        ->with('error', 'An error occurred while updating the event.');
    }
  }
  
  /**
   * Display bookings for a specific event.
   *
   * @param Event $event
   * @return View
   */
  public function bookings(Event $event): View
  {
    $bookings = $event->bookings()->with('attendee')->latest()->paginate(15);
    $totalTicketsSold = $event->bookings()->sum('quantity');
    return view('organizer.bookings.index', compact('event', 'bookings', 'totalTicketsSold'));
  }

  /**
   * Soft-delete (cancel) an organizer's own event.
   *
   * @param Event $event
   * @return RedirectResponse
   */
  public function cancel(Event $event,OrganizerCancelEventFeature $organizerCancelEventFeature): RedirectResponse
  {
    try {
      $organizerCancelEventFeature->handle($event->id, auth()->id());

      return redirect()->route('organizer.dashboard')->with('success', 'Your event has been cancelled successfully.');
    } catch (Exception $e) {
      Log::error('Organizer Event Cancellation Failed: ' . $e->getMessage());

      return back()->with('error', 'Could not cancel the event. Please try again.');
    }
  }
}