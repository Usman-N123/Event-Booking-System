<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\DTOs\Event\EventCreateDTO;
use App\DTOs\Event\EventUpdateDTO;
use App\Features\Event\CreateEventFeature;
use App\Features\Event\UpdateEventFeature;
use App\Http\Requests\IndexEventRequest;
use App\DTOs\Event\EventFilterDTO;
use App\Models\Event;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Exception;

class EventController extends Controller
{
  public function __construct(
    protected CreateEventFeature $createEventFeature,
    protected UpdateEventFeature $updateEventFeature,
    protected EventRepositoryInterface $eventRepository
  ) {}

  /**
   * Display a paginated, filtered listing of public events.
   *
   * @param IndexEventRequest $request
   * @return View|RedirectResponse
   */
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

  /**
   * Display the specified event.
   *
   * @param Event $event
   * @return View
   */
  public function show(Event $event): View
  {
    return view('events.show', compact('event'));
  }

  /**
   * Store a newly created event.
   *
   * @param StoreEventRequest $request
   * @return RedirectResponse
   */
  public function store(StoreEventRequest $request): RedirectResponse
  {
    try {
      $dto = EventCreateDTO::fromRequest($request);
      $this->createEventFeature->handle($dto);

      return redirect()->route('organizer.dashboard')
        ->with('success', 'Event created successfully and is pending admin approval.');
    } catch (Exception $e) {
      Log::error('Event Creation Failed: ' . $e->getMessage());

      return back()->withInput()
        ->with('error', 'An error occurred while creating the event.');
    }
  }

  /**
   * Show the edit form for an organizer's event.
   *
   * @param Event $event
   * @return View|RedirectResponse
   */
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
   * @param UpdateEventRequest $request
   * @param Event $event
   * @return RedirectResponse
   */
  public function update(UpdateEventRequest $request, Event $event): RedirectResponse
  {
    if ($event->organizer_id !== auth()->id()) {
      abort(403, 'You do not own this event.');
    }

    try {
      $dto = EventUpdateDTO::fromRequest($request, $event->id);
      $this->updateEventFeature->handle($dto);

      return redirect()->route('organizer.dashboard')
        ->with('success', 'Event updated successfully.');
    } catch (Exception $e) {
      Log::error('Event Update Failed: ' . $e->getMessage());

      return back()->withInput()
        ->with('error', 'An error occurred while updating the event.');
    }
  }

  public function bookings(Event $event): View
  {
    $bookings = $event->bookings()->with('attendee')->latest()->paginate(15);
    $totalTicketsSold = $event->bookings()->sum('quantity');
    return view('organizer.bookings.index', compact('event', 'bookings', 'totalTicketsSold'));
  }

}