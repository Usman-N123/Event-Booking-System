<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\EventController;
use App\Http\Controllers\Web\BookingController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\AdminEventController;

Route::get('/', [HomeController::class, 'welcome'])->name('home');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

Route::middleware(['auth'])->group(function () {

  Route::get('/attendee/dashboard', [DashboardController::class, 'attendee'])->name('attendee.dashboard');
  Route::get('/attendee/bookings/{booking}', [BookingController::class, 'show'])->name('attendee.bookings.show');
  Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

  Route::middleware(['role:organizer'])->group(function () {
    Route::get('/organizer/dashboard', [DashboardController::class, 'organizer'])->name('organizer.dashboard');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
  });

  Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::patch('/admin/events/{event}/approve', [AdminEventController::class, 'approve'])->name('admin.events.approve');
    Route::delete('/admin/events/{event}/reject', [AdminEventController::class, 'reject'])->name('admin.events.reject');
    Route::delete('/admin/events/{event}/cancel', [AdminEventController::class, 'cancel'])->name('admin.events.cancel');
    Route::get('/admin/events/{event}/noc', [AdminEventController::class, 'downloadNoc'])->name('admin.events.noc');
  });
});

require __DIR__.'/auth.php';