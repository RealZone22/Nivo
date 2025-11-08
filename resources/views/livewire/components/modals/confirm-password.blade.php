<div class="sm:w-96">
    <div class="flex items-center justify-between bg-neutral-50/60 p-4 dark:bg-neutral-950/20">
        <h3 class="font-semibold tracking-wide text-neutral-900 dark:text-white">
            {{ $title }}
        </h3>
        <button wire:click="forceCloseModal" aria-label="close modal" class="cursor-pointer">
            <i class="icon-x"></i>
        </button>
    </div>


    <div class="px-4 mt-3">
        {{ $description }}
    </div>

    <form wire:submit="confirmPassword">
        <div class="p-4">
            <x-password wire:model="password" label="{{ __('confirm-password.password') }}" required/>
        </div>

        <x-modal.footer>
            <x-button type="submit" loading="confirmPassword" class="w-full lg:w-32 lg:ms-3">
                {{ __('messages.buttons.confirm') }}
            </x-button>
        </x-modal.footer>
    </form>
</div>
