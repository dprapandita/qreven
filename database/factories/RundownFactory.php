<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Rundown;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rundown>
 */
class RundownFactory extends Factory
{
    protected $model = Rundown::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'nama_kegiatan' => fake()->words(2, true),
            'waktu_mulai' => now()->addWeek(),
            'waktu_selesai' => now()->addWeek()->addHour(),
            'deskripsi' => fake()->sentence(),
        ];
    }
}
