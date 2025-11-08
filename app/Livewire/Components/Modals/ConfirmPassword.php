<?php

namespace App\Livewire\Components\Modals;

use App\Traits\HasLoggingAndView;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;

class ConfirmPassword extends ModalComponent
{
    use HasLoggingAndView;

    #[Locked]
    public $title;

    #[Locked]
    public $description;

    #[Locked]
    public $event;

    #[Locked]
    public $dispatch;

    public $password;

    public function confirmPassword()
    {
        $this->validate([
            'password' => 'required|string',
        ]);

        if (! Hash::check($this->password, auth()->user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('validation.current_password')],
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        if ($this->event) {
            $this->dispatch('auth.passwordConfirmed', $this->event);
        }
        if ($this->dispatch) {
            $this->dispatch(
                $this->dispatch['event'],
                ...$this->dispatch['args']
            );
        }

        $this->closeModal();
    }

    public function forceCloseModal()
    {
        $this->forceClose()->closeModal();
    }

    public function mount()
    {
        if (! $this->title) {
            $this->title = __('confirm-password.title');
        }

        if (! $this->description) {
            $this->description = __('confirm-password.description');
        }
    }

    public function render()
    {
        return view('livewire.components.modals.confirm-password');
    }
}
