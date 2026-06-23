<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\DTOs\Event\EventCreateDTO;
use App\Features\Event\CreateEventFeature;
use App\Http\Resources\EventResource;
use App\Http\Requests\IndexEventRequest;
use App\DTOs\Event\EventFilterDTO;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class EventController extends Controller {
  
  public function __construct(
    protected CreateEventFeature $createEventFeature,
	protected EventRepositoryInterface $eventRepository
  ) {}

  /**
   * Store a newly created event.
   *
   * @param StoreEventRequest $request
   * @return JsonResponse
   */
  public function store(StoreEventRequest $request): JsonResponse {
    try {
      // 1. Map validated request to DTO
      $dto = EventCreateDTO::fromRequest($request);

      // 2. Execute Business Logic
      $event = $this->createEventFeature->handle($dto);

      // 3. Return Strictly Formatted Success Response
      return response()->json([
        'status' => true,
        'message' => 'Event created successfully and is pending admin approval.',
        'data' => new EventResource($event),
      ], 201);

    } catch (Exception $e) {
      // 4. Log the error and return Strictly Formatted Error Response
      Log::error('Event Creation Failed: ' . $e->getMessage());
      
      return response()->json([
        'status' => false,
        'message' => 'An error occurred while creating the event.',
        'errors' => ['server' => ['Internal Server Error']],
      ], 500);
    }
  }

  /**
   * Display a listing of public events with filters.
   *
   * @param IndexEventRequest $request
   * @return JsonResponse
   */
    public function index(IndexEventRequest $request): JsonResponse {
        try {
            $dto = EventFilterDTO::fromRequest($request);
            
            $events = $this->eventRepository->getPublicEvents($dto);

            return response()->json([
                'status' => true,
                'message' => 'Events retrieved successfully.',
                'data' => EventResource::collection($events)->response()->getData(true), // Preserves pagination metadata
            ], 200);

        } catch (Exception $e) {
            Log::error('Event Fetching Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching events.',
                'errors' => ['server' => ['Internal Server Error']],
            ], 500);
        }
    }
}