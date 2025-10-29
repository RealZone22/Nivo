<?php

namespace App\Livewire\Auth;

use App\Facades\Unsplash;
use App\Models\User;
use App\Traits\HasLoggingAndView;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    use HasLoggingAndView, WithRateLimiting;

    public $unsplash = [];
    public $rateLimitTime;
    public $user;
    public $username;
    public $password;
    public $remember;
    public $twoFactorEnabled = false;
    public $useRecoveryCode = false;
    public $twoFactorCode;

    public function attemptLogin()
    {
        $this->validate([
            'username' => 'required',
            'password' => 'required',
            'remember' => 'nullable|boolean',
        ]);

        $this->checkIfUserExists($this->username);

        if (!$this->user) {
            return;
        }

        if (!Hash::check($this->password, $this->user->password)) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        if ($this->user->disabled) {
            Auth::logout();
            throw ValidationException::withMessages([
                'username' => __('auth::login.user_disabled'),
            ]);
        }

        if ($this->user->two_factor_enabled) {
            $this->twoFactorEnabled = true;

            return;
        }

        Auth::login($this->user, $this->remember);

        redirect()->intended(route('dashboard'));
    }

    public function checkIfUserExists($username)
    {
        $this->user = null;
        if (blank($username)) {
            return;
        }

        if ($this->setRateLimit()) {
            return;
        }

        $this->validate([
            'username' => 'required|exists:users,username',
        ], [
            'username.exists' => __('auth.login.user_not_found'),
        ]);

        $this->user = User::where('username', $username)->first();
        if ($this->user->oauth_id) {
            $this->user = null;
            $this->addError('username', __('auth.login.user_not_found'));

            return;
        }

        $this->resetErrorBag('username');
    }

    public function setRateLimit(): bool
    {
        try {
            $this->rateLimit(settings('auth.rate_limit', config('settings.auth.rate_limit')));
        } catch (TooManyRequestsException $exception) {
            $this->rateLimitTime = $exception->secondsUntilAvailable;

            return true;
        }

        return false;
    }

    public function checkTwoFactorCode(): void
    {
        if ($this->setRateLimit()) {
            return;
        }

        $this->checkIfUserExists($this->username);

        if (!$this->user->two_factor_enabled) {
            return;
        }

        if ($this->useRecoveryCode) {
            foreach ($this->user->recoveryCodes as $recoveryCode) {
                if (Hash::check($this->twoFactorCode, $recoveryCode->code)) {
                    $recoveryCode->delete();

                    Auth::login($this->user, $this->remember);

                    redirect()->intended(route('dashboard'));
                }
            }

            throw ValidationException::withMessages([
                'twoFactorCode' => __('auth::login.recovery_code_invalid'),
            ]);
        }

        if ($this->user->checkTwoFACode($this->twoFactorCode)) {
            Auth::login($this->user, $this->remember);

            redirect()->intended(route('dashboard'));
        }
        throw ValidationException::withMessages([
            'twoFactorCode' => __('auth::login.two_factor_code_invalid'),
        ]);
    }

    public function changeLanguage($language)
    {
        if ($language === request()->cookie('language')) {
            return;
        }
        cookie()->queue(cookie()->forget('language'));
        cookie()->queue(cookie()->forever('language', $language));

        $this->redirect(route('auth.login'));
    }

    public function mount()
    {
        $this->unsplash = Unsplash::returnBackground();

        if ($this->unsplash['error'] !== null) {
            $this->log($this->unsplash['error'], 'error');
        }
    }


    public function render()
    {
        return $this->renderView('livewire.auth.login', __('auth.login.title'));
    }
}
