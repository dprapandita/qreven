<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class SocialiteController extends Controller
{
    public function redirect(string $provider): SymfonyRedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(Request $request, string $provider): RedirectResponse
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (InvalidStateException) {
            return $this->failedLogin('Login dibatalkan atau sesi sudah kedaluwarsa.');
        } catch (\Throwable $exception) {
            report($exception);

            return $this->failedLogin('Login gagal. Silakan coba lagi.');
        }

        $email = $socialUser->getEmail();

        if (! $email) {
            return $this->failedLogin('Akun tidak memiliki email yang bisa digunakan.');
        }

        $user = User::query()
            ->where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first()
            ?? User::query()->where('email', $email)->first();

        if ($user) {
            $user->update([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
            ]);
        } else {
            $user = User::forceCreate([
                'name' => $socialUser->getName() ?: $socialUser->getNickname() ?: 'Penyelenggara',
                'email' => $email,
                'email_verified_at' => now(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
            ]);
        }

        Auth::login($user, true);

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    private function failedLogin(string $message): RedirectResponse
    {
        return redirect()->route('login')->withErrors(['oauth' => $message]);
    }
}
