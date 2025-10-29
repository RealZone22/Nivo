<?php

use App\Livewire\Auth\Login;
use App\Livewire\Dashboard;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('/', Dashboard::class)->name('dashboard');
});

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::group(['middleware' => ['guest', 'throttle:10,1']], function () {
        Route::get('login', Login::class)->name('login');
    });

    Route::group(['middleware' => ['auth']], function () {
        Route::get('logout', function () {
            auth()->logout();

            return redirect()->route('auth.login');
        })->name('logout');
    });
});

Route::group(['prefix' => 'oauth', 'as' => 'oauth.'], function () {
    Route::get('{provider}', function ($provider) {
        if (!settings('auth.oauth.enabled', config('settings.auth.oauth.enabled'))) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    })->name('redirect');

    Route::get('{provider}/callback', function ($provider) {
        if (!settings('auth.oauth.enabled', config('settings.auth.oauth.enabled'))) {
            abort(404);
        }

        try {
            $providerUser = Socialite::driver($provider)->user();
            $user = User::where('oauth_id', $providerUser->id)->first(); // @phpstan-ignore-line

            if (!$user) {
                try {
                    $user = User::create([
                        'username' => $providerUser->getName(),
                        'email' => $providerUser->getEmail(),
                        'oauth_id' => $providerUser->getId(),
                    ]);
                } catch (Exception) {
                    $user = User::create([
                        'username' => $providerUser->getName() . $providerUser->getId(),
                        'email' => $providerUser->getEmail() . $providerUser->getId(),
                        'oauth_id' => $providerUser->getId(),
                    ]);
                }

                $user->generateTwoFASecret();
            }
        } catch (Exception) {
            return redirect()->route('auth.login');
        }

        auth()->login($user);

        return redirect('/');
    })->name('callback');
});
