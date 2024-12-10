<?php

namespace App\Modules\Traits;

use PragmaRX\Google2FA\Google2FA;

trait HasTwoFactoryCode
{

    public function verifyTFACode(string $code) : bool
    {
        return (new Google2FA())->verifyKey( $this->tfa_secret, $code);
    }

}
