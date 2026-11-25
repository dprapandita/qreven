<?php

namespace Database\Factories;

use App\Enums\TicketOrderStatus;
use App\Models\Event;
use App\Models\TicketOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TicketOrder>
 */
class TicketOrderFactory extends Factory
{
    protected $model = TicketOrder::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'event_id' => Event::factory(),
            'total_harga' => 100000,
            'status_pembayaran' => TicketOrderStatus::Pending->value,
            'expires_at' => now()->addMinutes(30),
        ];
    }

    public function paid(): static
    {
        return $this->state(fn () => [
            'status_pembayaran' => TicketOrderStatus::Paid->value,
            'paid_at' => now(),
            'expires_at' => null,
        ]);
    }
}
