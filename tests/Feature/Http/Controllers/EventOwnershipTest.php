<?php

use App\Models\Booth;
use App\Models\Event;
use App\Models\Rundown;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

function createEventFor(User $user, string $name = 'Konser'): Event
{
    return Event::factory()->for($user)->create([
        'nama_event' => $name,
        'subdomain' => fake()->unique()->slug(2),
    ]);
}

it('lists only events owned by or shared with the authenticated organizer', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $ownEvent = createEventFor($owner, 'Event Sendiri');
    createEventFor($other, 'Event Orang Lain');

    $this->actingAs($owner)
        ->get(route('events.index'))
        ->assertOk()
        ->assertSee('Event Sendiri')
        ->assertDontSee('Event Orang Lain');
});

it('blocks another organizer from managing an event', function (string $method) {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $event = createEventFor($owner);

    $this->actingAs($other);

    match ($method) {
        'show' => $this->get(route('events.show', $event))->assertNotFound(),
        'update' => $this->put(route('events.update', $event), [
            'nama_event' => 'Diubah',
            'subdomain' => 'diubah',
            'lokasi' => 'Bandung',
            'tanggal_mulai' => '2026-12-01 10:00:00',
            'tanggal_selesai' => '2026-12-02 10:00:00',
        ])->assertNotFound(),
        'destroy' => $this->delete(route('events.destroy', $event))->assertNotFound(),
    };

    $this->assertModelExists($event);
})->with(['show', 'update', 'destroy']);

it('scopes child resources to the organizer event', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $ownEvent = createEventFor($owner);
    $otherEvent = createEventFor($other);

    $ownTicketType = TicketType::factory()->for($ownEvent)->create();
    $otherTicketType = TicketType::factory()->for($otherEvent)->create();

    $ownBooth = Booth::factory()->for($ownEvent)->create();
    $otherBooth = Booth::factory()->for($otherEvent)->create();

    $ownRundown = Rundown::factory()->for($ownEvent)->create();
    $otherRundown = Rundown::factory()->for($otherEvent)->create();

    $this->actingAs($owner);

    $this->getJson(route('ticket-types.index', $ownEvent))
        ->assertOk()
        ->assertJsonCount(1)
        ->assertJsonFragment(['id' => $ownTicketType->id])
        ->assertJsonMissing(['id' => $otherTicketType->id]);

    $this->getJson(route('booths.index', $ownEvent))
        ->assertOk()
        ->assertJsonCount(1)
        ->assertJsonFragment(['id' => $ownBooth->id])
        ->assertJsonMissing(['id' => $otherBooth->id]);

    $this->getJson(route('rundowns.index', $ownEvent))
        ->assertOk()
        ->assertJsonCount(1)
        ->assertJsonFragment(['id' => $ownRundown->id])
        ->assertJsonMissing(['id' => $otherRundown->id]);
});

it('blocks creating a child resource under another organizer event', function (string $route, string $table, array $payload) {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $event = createEventFor($owner);

    $this->actingAs($other)
        ->postJson(route($route, $event), $payload)
        ->assertNotFound();

    $this->assertDatabaseCount($table, 0);
})->with([
    'ticket type' => [
        'ticket-types.store',
        'ticket_types',
        ['nama_tiket' => 'VIP', 'harga' => 100000, 'kuota' => 10],
    ],
    'booth' => [
        'booths.store',
        'booths',
        ['nama_booth' => 'Booth A', 'harga' => 100000],
    ],
    'rundown' => [
        'rundowns.store',
        'rundowns',
        ['nama_kegiatan' => 'Opening', 'waktu_mulai' => '2026-01-01 10:00:00', 'waktu_selesai' => '2026-01-01 11:00:00'],
    ],
]);

it('blocks updating or deleting a child resource under another organizer event', function (string $method) {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $event = createEventFor($owner);
    $ticketType = TicketType::factory()->for($event)->create();

    $this->actingAs($other);

    match ($method) {
        'update' => $this->putJson(route('ticket-types.update', [$event, $ticketType->id]), [
            'nama_tiket' => 'Diubah',
        ])->assertNotFound(),
        'destroy' => $this->deleteJson(route('ticket-types.destroy', [$event, $ticketType->id]))
            ->assertNotFound(),
    };

    $this->assertModelExists($ticketType);
})->with(['update', 'destroy']);

it('does not resolve a child resource through another event', function () {
    $owner = User::factory()->create();
    $ownEvent = createEventFor($owner);
    $otherEvent = createEventFor(User::factory()->create());
    $otherTicketType = TicketType::factory()->for($otherEvent)->create();

    $this->actingAs($owner)
        ->putJson(route('ticket-types.update', [$ownEvent, $otherTicketType->id]), [
            'nama_tiket' => 'Diubah',
        ])
        ->assertNotFound();

    $this->assertModelExists($otherTicketType);
});
