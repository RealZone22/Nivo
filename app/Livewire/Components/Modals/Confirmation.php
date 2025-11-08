<?php

namespace App\Livewire\Components\Modals;

use App\Traits\WithPasswordConfirmation;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class Confirmation extends ModalComponent
{
    use WithPasswordConfirmation;

    #[Locked]
    public $title;

    #[Locked]
    public $description;

    #[Locked]
    public $cancel;

    #[Locked]
    public $cancelColor;

    #[Locked]
    public $confirm;

    #[Locked]
    public $confirmColor;

    #[Locked]
    public $icon;

    #[Locked]
    public $iconColor;

    #[Locked]
    public $needsPasswordConfirmation;

    #[Locked]
    public $event;

    public function confirmAction()
    {
        if ($this->needsPasswordConfirmation && ! $this->hasPasswordConfirmedSession()) {
            return;
        }

        $this->dispatch('internal.confirmation.confirmed', $this->event);

        $this->dispatch('closeModal');
    }

    public function mount()
    {
        if ($this->needsPasswordConfirmation && ! $this->checkPasswordConfirmation()->passwordFunction('render')->checkPassword()) {
            return;
        }
    }

    public function render()
    {
        return view('livewire.components.modals.confirmation');
    }
}
