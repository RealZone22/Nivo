<?php

namespace App\Traits;

use Livewire\Attributes\On;

trait WithPasswordConfirmation
{
    private $passwordConfirmationData = [];

    public function hasPasswordConfirmedSession($expiration = 300): bool
    {
        $confirmedAt = session('auth.password_confirmed_at');

        return $confirmedAt && $confirmedAt >= (time() - $expiration);
    }

    public function checkPasswordConfirmation()
    {
        return $this;
    }

    public function passwordTitle($title)
    {
        $this->passwordConfirmationData['title'] = $title;

        return $this;
    }

    public function passwordDescription($description)
    {
        $this->passwordConfirmationData['description'] = $description;

        return $this;
    }

    public function passwordExpiration($expiration = 300)
    {
        $this->passwordConfirmationData['expiration'] = $expiration;

        return $this;
    }

    public function checkPassword()
    {
        if ($this->hasPasswordConfirmedSession($this->passwordConfirmationData['expiration'] ?? 300)) {
            return true;
        }

        $this->dispatch('openModal', 'components.modals.confirm-password', [
            'title' => $this->passwordConfirmationData['title'] ?? null,
            'description' => $this->passwordConfirmationData['description'] ?? null,
            'event' => $this->passwordConfirmationData['event'] ?? null,
            'dispatch' => $this->passwordConfirmationData['dispatch'] ?? null,
        ]);

        return false;
    }

    public function passwordFunction(string $callable, ...$args): static
    {
        $this->passwordConfirmationData['event'] = $callable.'('.implode(', ', $args).')';

        return $this;
    }

    public function passwordDispatch(string $dispatch, ...$args): static
    {
        $this->passwordConfirmationData['dispatch'] = [
            'event' => $dispatch,
            'args' => $args,
        ];

        return $this;
    }

    #[On('auth.passwordConfirmed')]
    public function handlePasswordConfirmation($event): void
    {
        if (preg_match('/^(\w+)\((.*)\)$/', $event, $matches)) {
            $methodName = $matches[1];
            $arguments = array_map('trim', explode(',', $matches[2]));

            if (method_exists($this, $methodName)) {
                call_user_func_array([$this, $methodName], $arguments);
            }
        }
    }
}
