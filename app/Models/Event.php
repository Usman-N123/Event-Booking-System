<?php

namespace App\Models;

use App\Enums\EventApprovalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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
    use HasFactory, SoftDeletes;

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

    /**
     * Get the public-facing banner URL.
     *
     * Seeded events store full external URLs (e.g., picsum.photos).
     * Organizer-uploaded events store a relative path in public storage.
     * This accessor handles both cases transparently so every view can
     * simply use `$event->banner_url` without any conditional logic.
     */
    protected function bannerUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $path = $this->banner_path;

                // Already an absolute URL (http/https) — use as-is.
                if (filter_var($path, FILTER_VALIDATE_URL)) {
                    return $path;
                }

                // Relative path stored in public disk.
                return Storage::disk('public')->url($path);
            }
        );
    }
}
