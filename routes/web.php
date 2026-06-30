<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\EventController;
use App\Http\Controllers\Web\BookingController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\AdminEventController;
use App\Http\Controllers\Web\AdminUserController;

Route::get('/', [HomeController::class, 'welcome'])->name('home');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

Route::middleware(['auth'])->group(function () {

    Route::get('/attendee/dashboard', [DashboardController::class, 'attendee'])->name('attendee.dashboard');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('attendee.bookings.show')->middleware('can:view,booking');

    Route::middleware(['role:organizer'])->group(function () {
        Route::get('/organizer/dashboard', [DashboardController::class, 'organizer'])->name('organizer.dashboard');

        Route::middleware(['organizer.approved'])->group(function () {
            Route::post('/events', [EventController::class, 'store'])->name('events.store');
            Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
            Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
        });

        Route::delete('/organizer/events/{event}', [EventController::class, 'cancel'])->name('organizer.events.cancel');

        Route::get('/events/{event}/bookings', [EventController::class, 'bookings'])->name('events.bookings')->middleware('can:view,event');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
        Route::patch('/admin/events/{event}/approve', [AdminEventController::class, 'approve'])->name('admin.events.approve');
        Route::delete('/admin/events/{event}/reject', [AdminEventController::class, 'reject'])->name('admin.events.reject');
        Route::delete('/admin/events/{event}/cancel', [AdminEventController::class, 'cancel'])->name('admin.events.cancel');
        Route::get('/admin/events/{event}/noc', [AdminEventController::class, 'downloadNoc'])->name('admin.events.noc');

        // User management (organizer approval + user delete)
        Route::patch('/admin/users/{user}/approve', [AdminUserController::class, 'approve'])->name('admin.users.approve');
        Route::patch('/admin/users/{user}/reject', [AdminUserController::class, 'reject'])->name('admin.users.reject');
        Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    });
});

require __DIR__.'/auth.php';