<?php

namespace App\Livewire\Account;

use App\Facades\Settings;
use App\Traits\HasLoggingAndView;
use App\Traits\WithConfirmation;
use App\Traits\WithPasswordConfirmation;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Profile extends Component
{
    use HasLoggingAndView, WithConfirmation, WithPasswordConfirmation;

    #[Url]
    public $tab;

    #[Url]
    public $passTab = 'password';

    public $firstName;

    public $lastName;

    public $username;

    public $email;

    public $language;

    public $theme;

    public $currentPassword;

    public $newPassword;

    public $confirmPassword;

    public function updateLanguageAndTheme()
    {
        $this->validate([
            'language' => 'nullable|string',
            'theme' => 'nullable|string|in:light,dark',
        ]);

        Settings::updateSetting('language', $this->language ?? 'en', scope: 'user', userId: auth()->id());
        Settings::updateSetting('theme', $this->theme ?? 'light', scope: 'user', userId: auth()->id());

        App::setLocale($this->language ?? 'en');

        Toaster::success(__('profile.notifications.profile_updated'));

        $this->redirect(route('account.profile'), true);
    }

    public function updateProfile()
    {
        $this->validate([
            'firstName' => 'nullable|string',
            'lastName' => 'nullable|string',
            'username' => 'required|string',
            'email' => 'required|email',
        ]);

        auth()->user()->update([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'username' => $this->username,
            'email' => $this->email,
        ]);

        Toaster::success(__('profile.notifications.profile_updated'));

        $this->redirect(route('account.profile'), true);
    }

    public function updatePassword()
    {
        if (auth()->user()->password === null) {
            $this->validate([
                'newPassword' => 'required',
                'confirmPassword' => 'required|same:newPassword',
            ]);
        } else {
            $this->validate([
                'currentPassword' => 'required',
                'newPassword' => 'required',
                'confirmPassword' => 'required|same:newPassword',
            ]);

            if (!Hash::check($this->currentPassword, auth()->user()->password)) {
                $this->addError('currentPassword', __('validation.current_password'));

                return;
            }
        }

        auth()->user()->update([
            'password' => $this->newPassword,
        ]);

        Toaster::success(__('profile.notifications.password_updated'));

        $this->redirect(route('account.profile'), true);
    }

    public function disableTwoFA($confirmed = false)
    {
        if (!$confirmed) {
            $this->dialog()
                ->question(__('profile.modals.disable_two_fa.title'),
                    __('profile.modals.disable_two_fa.description'))
                ->icon('icon-triangle-alert')
                ->needsPasswordConfirmation()
                ->confirm(__('profile.modals.disable_two_fa.buttons.disable'), 'danger')
                ->method('disableTwoFA', true)
                ->send();

            return;
        }

        auth()->user()->update([
            'two_factor_secret' => null,
            'two_factor_enabled' => false,
        ]);

        auth()->user()->generateTwoFASecret();
        auth()->user()->recoveryCodes()->delete();

        Toaster::success(__('profile.modals.disable_two_fa.notifications.two_fa_disabled'));

        $this->redirect(route('account.profile'), true);
    }

    public function deleteAccount($confirmed = false)
    {
        if (!settings('auth.profile.enable.delete_account', config('settings.auth.profile.delete_account', true))) {
            return;
        }

        if (!$confirmed) {
            $this->dialog()
                ->question(__('profile.modals.delete_account.title'),
                    __('profile.modals.delete_account.description'))
                ->icon('icon-triangle-alert')
                ->needsPasswordConfirmation()
                ->confirm(__('messages.buttons.delete'), 'danger')
                ->method('deleteAccount', true)
                ->send();

            return;
        }

        if (!auth()->user()->delete()) {
            Toaster::error(__('messages.notifications.something_went_wrong'));

            return;
        }

        Toaster::success(__('profile.modals.delete_account.notifications.account_deleted'));

        $this->redirect(route('auth.logout'));
    }

    public function logoutSession($sessionId)
    {
        if (!$this->checkPasswordConfirmation()->passwordFunction('logoutSession', $sessionId)->checkPassword()) {
            return;
        }

        auth()->user()->deleteSession($sessionId);

        Toaster::success(__('profile.sessions.notifications.logged_out'));

        $this->redirect(route('account.profile', ['tab' => 'sessions']), true);
    }

    public function logoutAllSessions($confirmed = false)
    {
        if (!$confirmed) {
            $this->dialog()
                ->question(__('profile.sessions.modals.logout_all.title'),
                    __('profile.sessions.modals.logout_all.description'))
                ->icon('icon-triangle-alert')
                ->needsPasswordConfirmation()
                ->confirm(__('messages.buttons.confirm'), 'danger')
                ->method('logoutAllSessions', true)
                ->send();

            return;
        }

        auth()->user()->revokeOtherSessions();

        Toaster::success(__('profile.sessions.notifications.logged_out_all'));

        $this->redirect(route('account.profile', ['tab' => 'sessions']), true);
    }

    public function mount()
    {
        if (empty($this->tab)) {
            $this->tab = 'overview';
        }

        if (empty($this->passTab)) {
            $this->passTab = 'password';
        }

        $user = auth()->user();

        $this->firstName = $user->first_name;
        $this->lastName = $user->last_name;
        $this->username = $user->username;
        $this->email = $user->email;

        $this->language = userSettings('language', 'en');
        $this->theme = userSettings('theme', 'light');

        $this->currentPassword = '';
        $this->newPassword = '';
        $this->confirmPassword = '';
    }

    #[On('refreshProfile')]
    public function render()
    {
        return $this->renderView('livewire.account.profile', __('profile.title'), 'components.layouts.app');
    }
}
