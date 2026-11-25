<?php

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\User as SocialiteUser;

uses(LazilyRefreshDatabase::class);

it('renders the organizer login page with both providers', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('Lanjutkan dengan Google')
        ->assertSee('Lanjutkan dengan GitHub');
});

it('redirects to the selected oauth provider', function () {
    Socialite::fake('google');

    $this->get(route('auth.redirect', ['provider' => 'google']))
        ->assertRedirect('https://socialite.fake/google/authorize');
});

it('rejects unsupported oauth providers', function () {
    $this->get(route('auth.redirect', ['provider' => 'facebook']))
        ->assertNotFound();
});

it('creates an organizer from a google account and logs them in', function () {
    Socialite::fake('google', SocialiteUser::fake([
        'id' => 'google-123',
        'name' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'avatar' => 'https://example.com/budi.jpg',
    ]));

    $this->get(route('auth.callback', ['provider' => 'google']))
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticated();

    $user = User::query()->where('email', 'budi@example.com')->firstOrFail();

    expect($user->provider)->toBe('google')
        ->and($user->provider_id)->toBe('google-123')
        ->and($user->avatar)->toBe('https://example.com/budi.jpg')
        ->and($user->password)->toBeNull()
        ->and($user->email_verified_at)->not->toBeNull();
});

it('links an existing email account to the new provider without duplicating users', function () {
    $user = User::factory()->create(['email' => 'budi@example.com']);

    Socialite::fake('github', SocialiteUser::fake([
        'id' => 'github-456',
        'name' => 'Budi Santoso',
        'email' => 'budi@example.com',
    ]));

    $this->get(route('auth.callback', ['provider' => 'github']))
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
    $this->assertDatabaseCount('users', 1);

    expect($user->refresh()->provider)->toBe('github')
        ->and($user->provider_id)->toBe('github-456');
});

it('logs in an existing social account by provider id', function () {
    $user = User::factory()->create([
        'email' => 'lama@example.com',
        'provider' => 'google',
        'provider_id' => 'google-123',
    ]);

    Socialite::fake('google', SocialiteUser::fake([
        'id' => 'google-123',
        'name' => 'Budi Santoso',
        'email' => 'baru@example.com',
    ]));

    $this->get(route('auth.callback', ['provider' => 'google']))
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseMissing('users', ['email' => 'baru@example.com']);
});

it('redirects back with an error when the provider returns no email', function () {
    Socialite::fake('github', SocialiteUser::fake([
        'id' => 'github-789',
        'name' => 'Tanpa Email',
        'email' => null,
    ]));

    $this->get(route('auth.callback', ['provider' => 'github']))
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('oauth');

    $this->assertGuest();
    $this->assertDatabaseCount('users', 0);
});

it('redirects back with an error when the oauth grant is denied', function () {
    Socialite::fake('google', fn () => throw new InvalidStateException);

    $this->get(route('auth.callback', ['provider' => 'google']))
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('oauth');

    $this->assertGuest();
});

it('redirects guests away from the dashboard', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});

it('shows the dashboard to an authenticated organizer', function () {
    $user = User::factory()->create(['name' => 'Budi Santoso']);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Budi Santoso');
});
