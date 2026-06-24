<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Laravel\Sanctum\HasApiTokens;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_picture_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    /**
     * Get the public-facing profile picture URL.
     *
     * Seeded users have a full pravatar.cc URL; real users have a relative
     * storage path. Returns null gracefully when no picture is set.
     */
    protected function profilePictureUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $path = $this->profile_picture_path;

                if (!$path) {
                    return null;
                }

                // Already an absolute URL — return it directly.
                if (filter_var($path, FILTER_VALIDATE_URL)) {
                    return $path;
                }

                // Relative path stored in public disk.
                return Storage::disk('public')->url($path);
            }
        );
    }
}
