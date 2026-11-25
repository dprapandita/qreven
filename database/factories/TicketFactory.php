<?php

namespace Database\Factories;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketOrder;
use App\Models\TicketType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_order_id' => TicketOrder::factory(),
            'ticket_type_id' => TicketType::factory(),
            'kode_tiket' => Str::uuid(),
            'status' => TicketStatus::Valid->value,
        ];
    }
}
