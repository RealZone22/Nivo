<?php

namespace App\Livewire\Components\Modals\TwoFactor;

use App\Traits\WithPasswordConfirmation;
use LivewireUI\Modal\ModalComponent;

class RegenerateRecoveryCodes extends ModalComponent
{
    use WithPasswordConfirmation;

    public $recoveryCodes = [];

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

    public function mount()
    {
        if (! $this->checkPasswordConfirmation()->passwordFunction('render')->checkPassword()) {
            return;
        }
    }

    public function render()
    {
        return view('livewire.components.modals.two-factor.regenerate-recovery-codes');
    }
}
