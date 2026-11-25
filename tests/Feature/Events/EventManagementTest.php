<?php

use App\Enums\EventRole;
use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

function eventPayload(array $overrides = []): array
{
    return array_merge([
        'nama_event' => 'Konser Baru',
        'subdomain' => 'konser-baru',
        'deskripsi' => 'Deskripsi',
        'lokasi' => 'Jakarta',
        'tanggal_mulai' => '2026-12-01 10:00:00',
        'tanggal_selesai' => '2026-12-02 10:00:00',
    ], $overrides);
}

it('creates an event with a normalized subdomain', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('events.store'), eventPayload(['subdomain' => 'My Event']))
        ->assertRedirect(route('events.index'));

    $event = Event::query()->where('nama_event', 'Konser Baru')->firstOrFail();

    expect($event->user_id)->toBe($user->id)
        ->and($event->subdomain)->toBe('my-event')
        ->and($event->status)->toBe(EventStatus::Draft)
        ->and($event->timezone)->toBe('UTC');
});

it('rejects invalid event input', function (array $overrides) {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('events.store'), eventPayload($overrides))
        ->assertSessionHasErrors(array_keys($overrides));

    $this->assertDatabaseCount('events', 0);
})->with([
    'missing subdomain' => [['subdomain' => '']],
    'reserved subdomain' => [['subdomain' => 'admin']],
    'subdomain is too long' => [['subdomain' => str_repeat('a', 64)]],
    'end date before start' => [['tanggal_selesai' => '2026-11-30 10:00:00']],
]);

it('rejects a duplicate subdomain', function () {
    Event::factory()->create(['subdomain' => 'taken']);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('events.store'), eventPayload(['subdomain' => 'taken']))
        ->assertSessionHasErrors('subdomain');
});

it('does not reveal another organizer event', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $event = Event::factory()->for($owner)->create();

    $this->actingAs($other)->get(route('events.show', $event))->assertNotFound();
    $this->actingAs($other)
        ->put(route('events.update', $event), eventPayload())
        ->assertNotFound();
    $this->actingAs($other)->delete(route('events.destroy', $event))->assertNotFound();

    $this->assertModelExists($event);
});

it('lets an administrator update an event but not delete it', function () {
    $owner = User::factory()->create();
    $administrator = User::factory()->create();
    $event = Event::factory()->for($owner)->create();

    $event->members()->attach($administrator->id, ['role' => EventRole::Administrator->value]);

    $this->actingAs($administrator)
        ->put(route('events.update', $event), eventPayload(['nama_event' => 'Diubah']))
        ->assertRedirect(route('events.show', $event));

    expect($event->refresh()->nama_event)->toBe('Diubah');

    $this->actingAs($administrator)
        ->delete(route('events.destroy', $event))
        ->assertForbidden();

    $this->assertModelExists($event);
});

it('forbids a finance member from updating an event', function () {
    $owner = User::factory()->create();
    $finance = User::factory()->create();
    $event = Event::factory()->for($owner)->create();

    $event->members()->attach($finance->id, ['role' => EventRole::Finance->value]);

    $this->actingAs($finance)
        ->put(route('events.update', $event), eventPayload())
        ->assertForbidden();
});

it('soft deletes an event for its owner', function () {
    $owner = User::factory()->create();
    $event = Event::factory()->for($owner)->create();

    $this->actingAs($owner)
        ->delete(route('events.destroy', $event))
        ->assertRedirect(route('events.index'));

    $this->assertSoftDeleted($event);
});

it('lists events owned by or shared with the organizer', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $stranger = User::factory()->create();

    $owned = Event::factory()->for($owner)->create(['nama_event' => 'Milik Sendiri']);
    $shared = Event::factory()->create(['nama_event' => 'Event Bersama']);
    $shared->members()->attach($owner->id, ['role' => EventRole::Finance->value]);
    $hidden = Event::factory()->create(['nama_event' => 'Event Lain']);

    $this->actingAs($owner)
        ->get(route('events.index'))
        ->assertOk()
        ->assertSee('Milik Sendiri')
        ->assertSee('Event Bersama')
        ->assertDontSee('Event Lain');

    $this->actingAs($stranger)
        ->get(route('events.index'))
        ->assertOk()
        ->assertDontSee('Milik Sendiri')
        ->assertDontSee('Event Bersama');
});

it('lets only the owner manage event members', function () {
    $owner = User::factory()->create();
    $administrator = User::factory()->create();
    $newMember = User::factory()->create();
    $event = Event::factory()->for($owner)->create();

    $event->members()->attach($administrator->id, ['role' => EventRole::Administrator->value]);

    $this->actingAs($administrator)
        ->post(route('members.store', $event), [
            'user_id' => $newMember->id,
            'role' => EventRole::Finance->value,
        ])
        ->assertForbidden();

    $this->actingAs($owner)
        ->post(route('members.store', $event), [
            'user_id' => $newMember->id,
            'role' => EventRole::Finance->value,
        ])
        ->assertCreated();

    $this->assertDatabaseHas('event_user', [
        'event_id' => $event->id,
        'user_id' => $newMember->id,
        'role' => EventRole::Finance->value,
    ]);

    $this->actingAs($owner)
        ->post(route('members.store', $event), [
            'user_id' => $newMember->id,
            'role' => EventRole::CheckIn->value,
        ])
        ->assertStatus(422);

    $this->actingAs($owner)
        ->delete(route('members.destroy', [$event, $newMember]))
        ->assertOk();

    $this->assertDatabaseMissing('event_user', [
        'event_id' => $event->id,
        'user_id' => $newMember->id,
    ]);
});
