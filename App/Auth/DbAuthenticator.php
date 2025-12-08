<?php

namespace App\Auth;

use App\Models\Pouzivatel;
use Framework\Auth\SessionAuthenticator;
use Framework\Core\IIdentity;

class DbAuthenticator extends SessionAuthenticator
{
    protected function authenticate(string $username, string $password): ?IIdentity
    {
        $pouzivatel = Pouzivatel::getOneByEmail($username);

        if ($pouzivatel === null) {
            return null;
        }

        if (password_verify($password, $pouzivatel->getHeslo())) {
            return $pouzivatel;
        }

        return null;
    }
}
