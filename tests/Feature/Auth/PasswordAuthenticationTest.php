<?php

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(LazilyRefreshDatabase::class);

describe('registration', function () {
    it('registers an organizer with email and password', function () {
        $this->post(route('register.store'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();

        $user = User::query()->where('email', 'budi@example.com')->firstOrFail();

        expect($user->name)->toBe('Budi Santoso')
            ->and($user->provider)->toBeNull()
            ->and($user->password)->not->toBe('password123');

        expect(Hash::check('password123', $user->password))->toBeTrue();
    });

    it('rejects an email that already exists', function () {
        User::factory()->create(['email' => 'budi@example.com']);

        $this->post(route('register.store'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertDatabaseCount('users', 1);
    });

    it('rejects a password without confirmation', function () {
        $this->post(route('register.store'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ])->assertSessionHasErrors('password');

        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    });

    it('rejects a password shorter than eight characters', function () {
        $this->post(route('register.store'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ])->assertSessionHasErrors('password');

        $this->assertGuest();
    });
});

describe('login', function () {
    it('logs in with the correct credentials', function () {
        $user = User::factory()->create([
            'email' => 'budi@example.com',
            'password' => 'secret123',
        ]);

        $this->post(route('login.store'), [
            'email' => 'budi@example.com',
            'password' => 'secret123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    });

    it('rejects an incorrect password', function () {
        User::factory()->create([
            'email' => 'budi@example.com',
            'password' => 'secret123',
        ]);

        $this->post(route('login.store'), [
            'email' => 'budi@example.com',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    });

    it('rejects an account that only has oauth login', function () {
        User::factory()->create([
            'email' => 'oauth@example.com',
            'password' => null,
            'provider' => 'google',
            'provider_id' => 'google-123',
        ]);

        $this->post(route('login.store'), [
            'email' => 'oauth@example.com',
            'password' => 'anything123',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    });
});

describe('logout', function () {
    it('logs out the authenticated organizer', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    });
});

it('renders email and password options on the login page', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('name="email"', false)
        ->assertSee('name="password"', false)
        ->assertSee('Lanjutkan dengan Google');
});

it('renders the registration page', function () {
    $this->get(route('register'))
        ->assertOk()
        ->assertSee('name="password_confirmation"', false)
        ->assertSee('Sudah punya akun?');
});
