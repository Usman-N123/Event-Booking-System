<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $event_id
 * @property int $attendee_id
 * @property int $quantity
 * @property float $total_amount
 * @property string $booking_reference
 * @property BookingStatus $status
 */

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'event_id', 'attendee_id', 'quantity', 'total_amount', 'booking_reference', 'status', 'pass_picture_path'
    ];

    protected $casts = [
      'total_amount' => 'decimal:2',
      'quantity' => 'integer',
      'status' => BookingStatus::class,
    ];

    public function event(): BelongsTo {
        return $this->belongsTo(Event::class);
    }

    public function attendee(): BelongsTo {
        return $this->belongsTo(User::class, 'attendee_id');
    }
}
