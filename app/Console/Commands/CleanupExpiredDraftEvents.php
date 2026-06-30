<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Support\Facades\Log;

class CleanupExpiredDraftEvents extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'event:cleanup-drafts';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Soft delete draft events that have passed their start date.';

  /**
   * Execute the console command.
   */
  public function handle(EventRepositoryInterface $eventRepository): void
  {
    $this->info('Starting cleanup of expired draft events...');

    try {
      $deletedCount = $eventRepository->deleteExpiredDrafts();

      $this->info("Cleanup complete. {$deletedCount} expired drafts were removed.");
      Log::info("Scheduled Task: Cleaned up {$deletedCount} expired draft events.");
    } catch (\Exception $e) {
      $this->error('An error occurred during cleanup. Check logs.');
      Log::error('Draft Cleanup Failed: ' . $e->getMessage());
    }
  }
}