<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManageEventRequest;
use App\DTOs\Event\ManageEventDTO;
use App\Features\Event\ManageEventFeature;
use App\Http\Resources\EventResource;
use App\Http\Requests\IndexEventRequest;
use App\DTOs\Event\EventFilterDTO;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class EventController extends Controller {
  
  public function __construct(
    protected ManageEventFeature $manageEventFeature,
	protected EventRepositoryInterface $eventRepository
  ) {}

  public function store(ManageEventRequest $request): JsonResponse {
    try {
      $dto = ManageEventDTO::fromRequest($request);

      $event = $this->manageEventFeature->handle($dto);

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