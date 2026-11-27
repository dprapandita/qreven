<?php

use App\Enums\EventRole;
use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

it('serves a published event on its subdomain', function () {
    $event = Event::factory()->published()->create([
        'subdomain' => 'acme',
        'nama_event' => 'Acme Festival',
    ]);

    $this->get('http://acme.localhost/')
        ->assertOk()
        ->assertSee('Acme Festival');
});

it('returns 404 for an unknown subdomain', function () {
    $this->get('http://unknown.localhost/')->assertNotFound();
});

it('returns 404 for a soft-deleted event subdomain', function () {
    $event = Event::factory()->published()->create(['subdomain' => 'deleted']);
    $event->delete();

    $this->get('http://deleted.localhost/')->assertNotFound();
});

it('hides a draft event from guests and non-members', function () {
    $event = Event::factory()->create([
        'subdomain' => 'draft',
        'status' => EventStatus::Draft->value,
    ]);

    $this->get('http://draft.localhost/')->assertNotFound();

    $this->actingAs(User::factory()->create())
        ->get('http://draft.localhost/')
        ->assertNotFound();
});

it('lets the owner and event staff preview a draft event', function () {
    $owner = User::factory()->create();
    $staff = User::factory()->create();

    $event = Event::factory()->for($owner)->create([
        'subdomain' => 'preview',
        'status' => EventStatus::Draft->value,
    ]);

    $event->members()->attach($staff->id, ['role' => EventRole::Administrator->value]);

    $this->actingAs($owner)->get('http://preview.localhost/')->assertOk();
    $this->actingAs($staff)->get('http://preview.localhost/')->assertOk();
});

it('does not serve reserved subdomains', function () {
    Event::factory()->published()->create(['subdomain' => 'admin']);

    $this->get('http://admin.localhost/')->assertNotFound();
});

it('serves a ticket list page on the event subdomain', function () {
    $event = Event::factory()->published()->create([
        'subdomain' => 'acme',
        'nama_event' => 'Acme Festival',
    ]);

    $event->ticketTypes()->create([
        'nama_tiket' => 'Early Bird',
        'harga' => 100000,
        'kuota' => 25,
        'capacity' => 50,
    ]);

    $event->ticketTypes()->create([
        'nama_tiket' => 'VIP',
        'harga' => 250000,
        'kuota' => 0,
        'capacity' => 10,
    ]);

    $this->get('http://acme.localhost/tickets')
        ->assertOk()
        ->assertSee('Acme Festival')
        ->assertSee('Early Bird')
        ->assertSee('Rp 100.000')
        ->assertSee('Tersedia')
        ->assertSee('VIP')
        ->assertSee('Habis');
});

it('returns json ticket list when requested on the event subdomain', function () {
    $event = Event::factory()->published()->create([
        'subdomain' => 'acme',
        'nama_event' => 'Acme Festival',
    ]);

    $event->ticketTypes()->create([
        'nama_tiket' => 'Reguler',
        'harga' => 50000,
        'kuota' => 10,
        'capacity' => 10,
    ]);

    $this->getJson('http://acme.localhost/tickets')
        ->assertOk()
        ->assertJsonPath('event', 'Acme Festival')
        ->assertJsonPath('ticket_types.0.nama_tiket', 'Reguler');
});
