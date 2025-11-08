<?php

namespace App\Models;

use App\Traits\WithSession;
use App\Traits\WithTwoFactorAuth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable, WithSession, WithTwoFactorAuth;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'remember_token',
        'password_reset_token',
        'password_reset_expiration',
    ];

    protected $casts = [
        'two_factor_enabled' => 'boolean',
        'force_change_password' => 'boolean',
        'force_activate_two_factor' => 'boolean',
        'disabled' => 'boolean',
        'password' => 'hashed',
    ];

    public function settings()
    {
        return $this->hasMany(Setting::class);
    }

    public function recoveryCodes()
    {
        return $this->hasMany(UserRecoveryCode::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function avatar()
    {
        if (userSettings('custom_avatar_url', userId: $this->id)) {
            return e(userSettings('custom_avatar_url', userId: $this->id));
        }

        $filePath = 'avatars/' . $this->id . '.png';
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->url($filePath);
        }

        $placeholders = [
            '{email}' => $this->email,
            '{email_md5}' => md5($this->email),
            '{username}' => $this->username,
            '{first_name}' => $this->first_name,
            '{last_name}' => $this->last_name,
        ];

        return str_replace(array_keys($placeholders), array_values($placeholders), settings('auth.default_avatar_url', config('settings.account.default_avatar_url')));
    }

    public function displayName()
    {
        if ($this->first_name || $this->last_name) {
            return $this->fullName();
        }

        return $this->username;
    }

    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
