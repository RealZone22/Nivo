<?php

namespace App\Livewire\Components\Modals\TwoFactor;

use App\Livewire\Account\Profile;
use App\Traits\WithPasswordConfirmation;
use Illuminate\Validation\ValidationException;
use LivewireUI\Modal\ModalComponent;
use Masmerise\Toaster\Toaster;

class ActivateTwoFA extends ModalComponent
{
    use WithPasswordConfirmation;

    public $twoFactorCode;

    public $recoveryCodes;

    public function activateTwoFA()
    {
        if (! $this->hasPasswordConfirmedSession()) {
            return;
        }

        $this->validate([
            'twoFactorCode' => 'required|digits:6',
        ]);

        if (! auth()->user()->checkTwoFACode($this->twoFactorCode, false)) {
            throw ValidationException::withMessages(['twoFactorCode' => __('profile.modals.activate_two_fa.invalid_two_factor_code')]);
        }

        $this->recoveryCodes = auth()->user()->generateRecoveryCodes();

        auth()->user()->update([
            'two_factor_enabled' => true,
        ]);

        auth()->user()->revokeOtherSessions();

        Toaster::success(__('profile.modals.activate_two_fa.notifications.two_fa_enabled'));

        $this->dispatch('refreshProfile')->to(Profile::class);
    }

    public function downloadRecoveryCodes()
    {
        if (! $this->hasPasswordConfirmedSession()) {
            return;
        }

        return response()->streamDownload(function () {
            echo implode(PHP_EOL, $this->recoveryCodes);
        }, 'recovery-codes-'.auth()->user()->username.'.txt');
    }

    public function regenerateRecoveryCodes()
    {
        if (! $this->hasPasswordConfirmedSession()) {
            return;
        }

        $this->recoveryCodes = auth()->user()->generateRecoveryCodes();
    }

    public function closeModal(): void
    {
        $this->redirect(route('account.profile'), true);
    }

    public function mount()
    {
        if (auth()->user()->two_factor_secret === null) {
            auth()->user()->generateTwoFASecret();
        }

        if (! $this->checkPasswordConfirmation()->passwordFunction('render')->checkPassword()) {
            return;
        }
    }

    public function render()
    {
        return view('livewire.components.modals.two-factor.activate-two-fa');
    }
}
