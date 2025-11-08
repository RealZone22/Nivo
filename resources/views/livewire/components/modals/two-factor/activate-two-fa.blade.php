<div class="sm:w-96">
    @if($this->hasPasswordConfirmedSession())
        @if(empty($recoveryCodes))
            <x-modal.header>
                {{ __('profile.modals.activate_two_fa.title') }}
            </x-modal.header>

            <div class="flex flex-col items-center my-4">
                <img src="data:image/svg+xml;base64,{{ auth()->user()->getTwoFactorImage() }}" alt="QR Code"
                     class="w-32 h-32 mx-auto mb-2 p-0.5 bg-white"/>
                <p>{{ decrypt(auth()->user()->two_factor_secret) }}</p>
            </div>

            <form wire:submit="activateTwoFA">
                <div class="p-4">
                    <x-input wire:model="twoFactorCode"
                             label="{{ __('profile.modals.activate_two_fa.two_fa_code') }}" required/>
                </div>

                <x-modal.footer>
                    <x-button type="submit" loading="activateTwoFA" class="w-full lg:w-32 lg:ms-3">
                        {{ __('messages.buttons.confirm') }}
                    </x-button>
                </x-modal.footer>
            </form>
        @else
            <x-modal.header>
                {{ __('profile.modals.recovery_codes.title') }}
            </x-modal.header>

            <div class="px-4 mt-3 text-center">
                {{ __('profile.modals.recovery_codes.description') }}
            </div>

            <div class="flex flex-col items-center my-4">
                @foreach($recoveryCodes as $recoveryCode)
                    <p class="mb-2">{{ $recoveryCode }}</p>
                @endforeach
            </div>


            <x-modal.footer>
                <x-button wire:click="regenerateRecoveryCodes" loading="regenerateRecoveryCodes" class="w-full lg:w-1/2">
                    {{ __('profile.modals.recovery_codes.buttons.regenerate') }}
                </x-button>
                <x-button wire:click="downloadRecoveryCodes" loading="downloadRecoveryCodes" class="w-full lg:w-1/2">
                    {{ __('profile.modals.recovery_codes.buttons.download') }}
                </x-button>
            </x-modal.footer>
        @endif
    @endif
</div>
