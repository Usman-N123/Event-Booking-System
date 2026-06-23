<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEventStatusRequest;
use App\Features\Event\UpdateEventStatusFeature;
use App\Features\Event\CancelEventFeature;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Exception;

class AdminEventController extends Controller
{
  /**
   * @param UpdateEventStatusFeature $updateEventStatusFeature
   */
  public function __construct(
    protected UpdateEventStatusFeature $updateEventStatusFeature,
    protected CancelEventFeature $cancelEventFeature
  ) {}

  /**
   * Approve a pending event.
   *
   * @param UpdateEventStatusRequest $request
   * @param Event $event
   * @return RedirectResponse
   */
  public function approve(UpdateEventStatusRequest $request, Event $event): RedirectResponse
  {
    try {
      $this->updateEventStatusFeature->handle($event->id, 'approved');

      return redirect()->route('admin.dashboard')
        ->with('success', 'Event approved successfully.');
    } catch (Exception $e) {
      Log::error('Event Approval Failed: ' . $e->getMessage());

      return back()->with('error', 'Could not approve the event.');
    }
  }

  /**
   * Reject a pending event.
   *
   * @param UpdateEventStatusRequest $request
   * @param Event $event
   * @return RedirectResponse
   */
  public function reject(UpdateEventStatusRequest $request, Event $event): RedirectResponse
  {
    try {
      $this->updateEventStatusFeature->handle($event->id, 'rejected');

      return redirect()->route('admin.dashboard')
        ->with('success', 'Event rejected successfully.');
    } catch (Exception $e) {
      Log::error('Event Rejection Failed: ' . $e->getMessage());

      return back()->with('error', 'Could not reject the event.');
    }
  }

  /**
   * Download the NOC document for an event.
   *
   * @param Event $event
   * @return \Symfony\Component\HttpFoundation\StreamedResponse
   */
  public function downloadNoc(Event $event)
  {
      if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($event->noc_document_path)) {
          abort(404, 'NOC Document not found.');
      }

      return \Illuminate\Support\Facades\Storage::disk('local')->download($event->noc_document_path, 'NOC_' . $event->slug . '.pdf');
  }

  /**
   * Cancel (soft-delete) an event.
   *
   * @param \Illuminate\Http\Request $request
   * @param Event $event
   * @return RedirectResponse
   */
  public function cancel(\Illuminate\Http\Request $request, Event $event): RedirectResponse
  {
    try {
      $this->cancelEventFeature->handle($event->id);

      return redirect()->route('admin.dashboard')
        ->with('success', 'Event cancelled and removed successfully.');
    } catch (Exception $e) {
      Log::error('Event Cancellation Failed: ' . $e->getMessage());

      return back()->with('error', 'Could not cancel the event.');
    }
  }
}