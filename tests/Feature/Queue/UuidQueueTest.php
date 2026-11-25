<?php

use App\Enums\TicketOrderStatus;
use App\Enums\TicketStatus;
use App\Jobs\ExpireTicketOrder;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\TicketOrder;
use App\Models\TicketOrderItem;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

uses(DatabaseMigrations::class);

it('processes a uuid model through the database queue and preserves infrastructure ids', function () {
    config(['queue.default' => 'database']);

    $owner = User::factory()->create();
    $buyer = User::factory()->create();
    $event = Event::factory()->for($owner)->published()->create([
        'subdomain' => 'queue-event',
    ]);
    $ticketType = TicketType::factory()->for($event)->create([
        'kuota' => 8,
        'capacity' => 10,
    ]);
    $order = TicketOrder::factory()->for($event)->for($buyer)->create([
        'total_harga' => '200000.00',
        'status_pembayaran' => TicketOrderStatus::Pending->value,
        'expires_at' => now()->subMinute(),
    ]);

    TicketOrderItem::factory()->create([
        'ticket_order_id' => $order->id,
        'ticket_type_id' => $ticketType->id,
        'quantity' => 2,
        'unit_price' => '100000.00',
        'subtotal' => '200000.00',
    ]);

    Ticket::factory()->count(2)->create([
        'ticket_order_id' => $order->id,
        'ticket_type_id' => $ticketType->id,
    ]);

    ExpireTicketOrder::dispatch($order);

    $job = DB::table('jobs')->first();
    $payload = json_decode($job->payload, true);

    expect($job)->not->toBeNull()
        ->and($job->id)->toBeInt()
        ->and($payload['displayName'])->toBe(ExpireTicketOrder::class)
        ->and($payload['data']['command'])->toContain($order->id);

    $exitCode = Artisan::call('queue:work', [
        '--once' => true,
        '--stop-when-empty' => true,
        '--sleep' => 0,
    ]);

    expect($exitCode)->toBe(0)
        ->and($order->refresh()->status_pembayaran)->toBe(TicketOrderStatus::Expired)
        ->and($order->inventory_released_at)->not->toBeNull()
        ->and($ticketType->refresh()->kuota)->toBe(10)
        ->and($order->tickets()->where('status', TicketStatus::Cancelled->value)->count())->toBe(2)
        ->and(DB::table('jobs')->count())->toBe(0)
        ->and(DB::table('failed_jobs')->count())->toBe(0);

    (new ExpireTicketOrder($order->refresh()))->handle();

    expect($ticketType->refresh()->kuota)->toBe(10);
});
