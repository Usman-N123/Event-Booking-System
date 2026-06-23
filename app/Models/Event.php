<?php

namespace App\Models;

use App\Enums\EventApprovalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

    /**
 * @property int $id
 * @property int $organizer_id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $category
 * @property string $city
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property float $price
 * @property int $total_seats
 * @property int $available_seats
 * @property string $banner_path
 * @property string $noc_document_path
 * @property EventApprovalStatus $approval_status
 */

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organizer_id',
        'title',
        'slug',
        'description',
        'category',
        'city',
        'start_date',
        'end_date',
        'price',
        'total_seats',
        'available_seats',
        'banner_path',
        'noc_document_path',
        'approval_status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'price' => 'decimal:2',
        'total_seats' => 'integer',
        'available_seats' => 'integer',
        'approval_status' => EventApprovalStatus::class,
    ];

    public function organizer(): BelongsTo {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function bookings(): HasMany {
        return $this->hasMany(Booking::class);
    }

    /**
     * Scope a query to only include upcoming events (excluding past events).
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now());
    }
}
