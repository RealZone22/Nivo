<?php

namespace App\Traits;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

trait WithTwoFactorAuth
{
    public function generateTwoFASecret()
    {
        $twoFactor = new Google2FA;
        $this->update(['two_factor_secret' => encrypt($twoFactor->generateSecretKey())]);
    }

    public function generateRecoveryCodes()
    {
        $this->recoveryCodes()->delete();

        $recoveryCodes = [];

        for ($i = 0; $i < 8; $i++) {
            $recoveryCode = Str::password(10);
            $this->recoveryCodes()->create(['code' => Hash::make($recoveryCode)]);
            $recoveryCodes[] = $recoveryCode;
        }

        return $recoveryCodes;
    }

    public function checkTwoFACode(string $key, bool $checkRecovery = true)
    {
        if (blank($key)) {
            return false;
        }

        if ($checkRecovery) {
            $recoveryCodes = $this->recoveryCodes;

            foreach ($recoveryCodes as $recoveryCode) {
                if (Hash::check($key, $recoveryCode->code)) {
                    $recoveryCode->delete();

                    return true;
                }
            }
        }

        $twoFactor = new Google2FA;
        $twoFactorSecret = decrypt($this->two_factor_secret);

        return $twoFactor->verifyKey($twoFactorSecret, $key);
    }

    public function getTwoFactorImage($format = 'svg', $size = 200): string
    {
        if (blank($this->two_factor_secret)) {
            $this->generateTwoFASecret();
        }
        $twoFactor = new Google2FA;
        $QRCode = $twoFactor->getQRCodeUrl(settings('internal.app.name'), $this->email, decrypt($this->two_factor_secret));

        return base64_encode(QrCode::format($format)->size($size)->generate($QRCode));
    }
}
