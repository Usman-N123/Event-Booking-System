<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The model the factory corresponds to.
     */
    protected $model = User::class;

    /**
     * The shared, pre-hashed version of 'password123' to avoid
     * re-hashing on every record (major performance win for 56+ users).
     */
    protected static string $hashedPassword;

    /**
     * Define the model's default state.
     * Defaults to the least-privileged role: attendee.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Cache the bcrypt hash so it is only computed once per seeding run.
        static::$hashedPassword ??= Hash::make('password123');

        $emailDomains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'company.com', 'techcorp.pk'];

        return [
            'name'                 => $this->faker->name(),
            'email'                => $this->faker->unique()->userName() . '@' . $this->faker->randomElement($emailDomains),
            'email_verified_at'    => now(),
            'password'             => static::$hashedPassword,
            'role'                 => UserRole::Attendee,
            'profile_picture_path' => 'https://i.pravatar.cc/300?u=' . $this->faker->unique()->uuid(),
            'remember_token'       => Str::random(10),
        ];
    }

    // -------------------------------------------------------------------------
    // Role State Methods
    // -------------------------------------------------------------------------

    /**
     * Set the user role to Admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::Admin,
        ]);
    }

    /**
     * Set the user role to Organizer.
     */
    public function organizer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::Organizer,
        ]);
    }

    /**
     * Set the user role to Attendee.
     */
    public function attendee(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => UserRole::Attendee,
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
