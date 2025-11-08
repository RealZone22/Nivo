<div>
    <div class="flex relative min-h-screen">
        <div class="absolute inset-0 z-[-1]" style="{{ $unsplash['css'] }}"></div>
        <div class="justify-center m-auto md:w-1/3 max-w-sm w-full">
            <div class="mb-4">
                <img src="{{ asset('img/Logo.png') }}" alt="Logo"
                     class="size-20 mx-auto">
            </div>

            <x-card class="space-y-4 mx-auto">
                @if($user)
                    <div class="rounded-radius border border-outline dark:border-outline-dark">
                        <div class="flex p-1 relative">
                            <img
                                src="{{ $user->avatar() }}"
                                alt="Avatar"
                                class="rounded-full w-8 h-8 m-1">
                            <p class="absolute top-1/2 left-1/2 translate-x-[-50%] translate-y-[-50%]">{{ $user->username }}</p>
                        </div>
                    </div>
                @endif

                @if ($rateLimitTime >= 1)
                    <div wire:poll.1s="setRateLimit">
                        <x-alert type="error">
                            {{ __('auth.throttle', ['seconds' => $rateLimitTime]) }}
                        </x-alert>
                    </div>
                @endif
                @if($twoFactorEnabled)
                    <form class="space-y-4" wire:submit="checkTwoFactorCode">
                        @if($useRecoveryCode)
                            <x-input wire:model="twoFactorCode" label="{{ __('auth.login.recovery_code') }}"
                                     required
                                     autofocus>
                                <x-slot:hint>
                                        <span class="hover:underline cursor-pointer"
                                              wire:click="$set('useRecoveryCode', false)">
                                            {{ __('auth.login.use_two_factor') }}
                                        </span>
                                </x-slot:hint>
                            </x-input>
                        @else
                            <x-input wire:model="twoFactorCode" label="{{ __('auth.login.two_factor_code') }}"
                                     required
                                     autofocus>
                                <x-slot:hint>
                                        <span class="hover:underline cursor-pointer"
                                              wire:click="$set('useRecoveryCode', true)">
                                            {{ __('auth.login.use_recovery_code') }}
                                        </span>
                                </x-slot:hint>
                            </x-input>
                        @endif

                        <x-button class="w-full" type="submit" loading="checkTwoFactorCode">
                            {{ __('auth.login.buttons.login') }}
                        </x-button>
                    </form>
                @else
                    <form class="space-y-4" wire:submit="attemptLogin">
                        <x-input wire:model="username" wire:blur="checkIfUserExists($event.target.value)"
                                 label="{{ __('auth.login.username') }}" required
                                 autofocus/>
                        <x-password wire:model="password" label="{{ __('auth.login.password') }}" required/>

                        <x-checkbox wire:model="remember" label="{{ __('auth.login.remember_me') }}"/>

                        <x-button class="w-full" type="submit" loading="attemptLogin">
                            {{ __('auth.login.buttons.login') }}
                        </x-button>
                    </form>

                    @if(settings('auth.oauth.enabled', config('settings.auth.oauth.enabled')) && !$twoFactorEnabled)
                        <x-divider/>

                        <x-button class="w-full" link="{{ route('oauth.redirect', ['provider' => 'custom']) }}"
                                  :color="settings('auth.oauth.login_color', config('settings.auth.oauth.login_color'))">
                            {{ settings('auth.oauth.login_text', config('settings.auth.oauth.login_text')) }}
                        </x-button>
                    @endif
                @endif
            </x-card>

            @if($unsplash['error'] == null)
                <div class="absolute bottom-0 left-0 p-4 text-white">
                <span class="text-sm" wire:ignore>
                    <a href="{{ $unsplash['photo'] }}">{{ __('auth.login.photo') }}</a>,
                    <a href="{{ $unsplash['authorURL'] }}">{{ $unsplash['author'] }}</a>,
                    <a href="{{ $unsplash['utm'] }}">Unsplash</a>
                </span>
                </div>
            @endif

            <div class="absolute bottom-6 left-0 p-4 sm:bottom-0 sm:right-0 sm:left-auto">
                <x-select wire:change="changeLanguage($event.target.value)">
                    <option value="en"
                            @if(app()->getLocale() == 'en') selected @endif>{{ __('messages.languages.en') }}</option>
                    <option value="de"
                            @if(app()->getLocale() == 'de') selected @endif>{{ __('messages.languages.de') }}</option>
                </x-select>
            </div>
        </div>
    </div>
</div>
