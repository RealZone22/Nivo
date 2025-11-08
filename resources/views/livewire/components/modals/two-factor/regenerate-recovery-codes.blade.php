<div class="sm:w-96">
    @if($this->hasPasswordConfirmedSession())
        <x-modal.header>
            {{ __('profile.modals.recovery_codes.title') }}
        </x-modal.header>

        <div class="px-4 mt-3 text-center">
            {{ __('profile.modals.recovery_codes.description') }}
        </div>

        <div class="flex flex-col items-center my-4" wire:init="regenerateRecoveryCodes">
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
</div>
