<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\AdminController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
  Route::get('/events', [EventController::class, 'index']);
  Route::post('/events', [EventController::class, 'store']);
  Route::post('/bookings', [BookingController::class, 'store']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
  Route::patch('/events/{event}/status', [AdminController::class, 'updateEventStatus'])->name('events.status.update');
  Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
});