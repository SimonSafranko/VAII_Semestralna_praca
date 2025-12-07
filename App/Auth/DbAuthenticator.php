<?php

namespace App\Auth;

use App\Models\Pouzivatel;
use Framework\Auth\SessionAuthenticator;
use Framework\Core\IIdentity;

/**
 * Authenticator, ktorý overuje prihlasovacie údaje voči tabuľke 'pouzivatel' v databáze.
 */
class DbAuthenticator extends SessionAuthenticator
{
    /**
     * Implementácia autentikácie: nájde používateľa podľa emailu a overí heslo.
     */
    protected function authenticate(string $username, string $password): ?IIdentity
    {
        // 1. Nájdi Používateľa podľa emailu
        $pouzivatel = Pouzivatel::getOneByEmail($username);

        if ($pouzivatel === null) {
            // Používateľ s daným emailom neexistuje
            return null;
        }

        // 2. Overenie hesla pomocou hashovacieho algoritmu
        // password_verify porovnáva zadané heslo s hashom uloženým v DB
        if (password_verify($password, $pouzivatel->getHeslo())) {
            // Prihlásenie úspešné, vráť Pouzivatel objekt, ktorý je IIdentity
            return $pouzivatel;
        }

        // Heslo nesprávne
        return null;
    }
}