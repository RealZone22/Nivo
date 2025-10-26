<?php

namespace App\Livewire\Auth;

use App\Traits\HasLoggingAndView;
use Livewire\Component;

class Login extends Component
{
    use HasLoggingAndView;

    public function render()
    {
        return $this->renderView('livewire.auth.login', __('auth.login.title'));
    }
}
