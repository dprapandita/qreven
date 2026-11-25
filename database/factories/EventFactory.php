<?php

namespace Database\Factories;

use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nama_event' => fake()->words(3, true),
            'subdomain' => fake()->unique()->slug(2),
            'deskripsi' => fake()->sentence(),
            'lokasi' => fake()->city(),
            'tanggal_mulai' => now()->addWeek(),
            'tanggal_selesai' => now()->addWeek()->addDay(),
            'timezone' => 'UTC',
            'status' => EventStatus::Draft->value,
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => EventStatus::Published->value,
        ]);
    }
}
