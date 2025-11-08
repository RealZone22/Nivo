<?php

namespace App\Livewire\Components\Modals;

use App\Facades\Settings;
use App\Traits\HasLoggingAndView;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;
use Masmerise\Toaster\Toaster;

class ChangeAvatar extends ModalComponent
{
    use HasLoggingAndView, WithFileUploads;

    public $avatarUrl;

    public $avatar;

    public function changeAvatar()
    {
        if (!settings('auth.profile.enable.change_avatar', config('settings.auth.profile.enable_change_avatar', true))) {
            return;
        }

        if ($this->avatar) {
            $this->validate([
                'avatar' => 'image:allow_svg|max:10000',
            ]);

            $this->avatar->storeAs('avatars', auth()->id() . '.png', 'public');

            Toaster::success(__('profile.modals.change_avatar.notifications.avatar_changed'));

            $this->redirect(route('account.profile'), true);

            return;
        }

        if ($this->avatarUrl) {

            Settings::updateSetting('custom_avatar_url', htmlspecialchars($this->avatarUrl), scope: 'user', userId: auth()->id());

            Toaster::success(__('profile.modals.change_avatar.notifications.avatar_changed'));

            $this->redirect(route('account.profile'), true);
        }

    }

    public function resetAvatar()
    {
        if (!settings('auth.profile.enable.change_avatar', config('settings.auth.profile.enable_change_avatar', true))) {
            return;
        }

        Storage::disk('public')->delete('avatars/' . auth()->id() . '.png');

        Settings::deleteSetting('custom_avatar_url', scope: 'user', userId: auth()->id());

        Toaster::success(__('profile.modals.change_avatar.notifications.avatar_reset'));

        $this->redirect(route('account.profile'), true);
    }

    public function mount()
    {
        if (!settings('auth.profile.enable.change_avatar', config('settings.auth.profile.enable_change_avatar', true))) {
            abort(403);
        }

        $this->avatarUrl = auth()->user()->custom_avatar_url;
    }

    public function render()
    {
        return view('livewire.components.modals.change-avatar');
    }
}
