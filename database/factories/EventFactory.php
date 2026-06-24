<?php

namespace Database\Factories;

use App\Enums\EventApprovalStatus;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * The model the factory corresponds to.
     */
    protected $model = Event::class;

    /**
     * Curated pool of realistic event titles for Pakistan's tech and culture scene.
     */
    private array $eventTitles = [
        'AI Summit 2026',
        'Lahore Tech Expo',
        'Summer Jazz Festival',
        'Pakistan Startup Conference',
        'Digital Marketing Masterclass',
        'React & Vue.js Bootcamp',
        'Fintech Innovation Forum',
        'Islamabad Cyber Security Summit',
        'Creative Design Workshop 2026',
        'Cloud Computing Conclave',
        'Faisalabad Business Expo',
        'Open Source Developers Meetup',
        'Data Science Deep Dive Workshop',
        'Mobile App Development Sprint',
        'E-Commerce Growth Hackathon',
        'Blockchain & Web3 Forum',
        'DevOps & CI/CD Masterclass',
        'Women in Tech Pakistan',
        'UX Research & Usability Workshop',
        'Annual Entrepreneurship Summit',
        'Python for Data Analytics Bootcamp',
        'Green Tech Innovation Expo',
        'Cultural Fusion Music Night',
        'HR & Talent Acquisition Webinar',
        'Laravel & PHP Workshop',
        'Full-Stack Developer Conference',
        'SEO & Digital Growth Summit',
        'Islamabad Food & Lifestyle Fest',
        'Game Dev Jam 2026',
        'Pakistani Literature & Arts Fair',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title       = $this->faker->unique()->randomElement($this->eventTitles);
        $slug        = Str::slug($title) . '-' . $this->faker->unique()->numberBetween(1000, 9999);
        $totalSeats  = $this->faker->numberBetween(50, 500);
        $startDate   = $this->faker->dateTimeBetween('-2 months', '+10 months');
        $endDate     = (clone $startDate)->modify('+' . $this->faker->numberBetween(2, 72) . ' hours');
        $category    = $this->faker->randomElement(['Concert', 'Workshop', 'Webinar']);
        $city        = $this->faker->randomElement(['Lahore', 'Islamabad', 'Faisalabad']);
        $bannerIndex = $this->faker->numberBetween(1, 500);

        return [
            'organizer_id'      => User::factory()->organizer(),   // overridden in seeder
            'title'             => $title,
            'slug'              => $slug,
            'description'       => $this->faker->paragraph(4) . "\n\n" . $this->faker->paragraph(4),
            'category'          => $category,
            'city'              => $city,
            'start_date'        => $startDate,
            'end_date'          => $endDate,
            'price'             => $this->faker->randomFloat(2, 10.00, 500.00),
            'total_seats'       => $totalSeats,
            'available_seats'   => $totalSeats,  // seeder will decrement this
            'banner_path'       => "https://picsum.photos/seed/{$bannerIndex}/1200/600",
            'noc_document_path' => 'documents/dummy_noc.pdf',
            'approval_status'   => EventApprovalStatus::APPROVED,
        ];
    }

    // -------------------------------------------------------------------------
    // Approval Status States
    // -------------------------------------------------------------------------

    /**
     * Mark the event as approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => EventApprovalStatus::APPROVED,
        ]);
    }

    /**
     * Mark the event as pending (draft).
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => EventApprovalStatus::DRAFT,
        ]);
    }

    /**
     * Mark the event as rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => EventApprovalStatus::REJECTED,
        ]);
    }

    // -------------------------------------------------------------------------
    // Date States
    // -------------------------------------------------------------------------

    /**
     * Generate an event set entirely in the past.
     */
    public function past(): static
    {
        return $this->state(function (array $attributes) {
            $startDate = $this->faker->dateTimeBetween('-12 months', '-1 week');
            $endDate   = (clone $startDate)->modify('+' . $this->faker->numberBetween(2, 48) . ' hours');

            return [
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ];
        });
    }

    /**
     * Generate an event set entirely in the future.
     */
    public function upcoming(): static
    {
        return $this->state(function (array $attributes) {
            $startDate = $this->faker->dateTimeBetween('+1 week', '+12 months');
            $endDate   = (clone $startDate)->modify('+' . $this->faker->numberBetween(2, 72) . ' hours');

            return [
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ];
        });
    }
}
