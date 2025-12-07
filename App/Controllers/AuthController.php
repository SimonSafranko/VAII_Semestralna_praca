<?php

namespace App\Controllers;

use App\Configuration;
use Exception;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;
use Framework\Http\Responses\ViewResponse;
use App\Models\Pouzivatel;
use App\Models\Hrac;
use App\Models\Klub;

/**
 * Class AuthController
 *
 * This controller handles authentication actions such as login, logout, and redirection to the login page. It manages
 * user sessions and interactions with the authentication system.
 *
 * @package App\Controllers
 */
class AuthController extends BaseController
{
    /**
     * Redirects to the login page.
     *
     * This action serves as the default landing point for the authentication section of the application, directing
     * users to the login URL specified in the configuration.
     *
     * @return Response The response object for the redirection to the login page.
     */
    public function index(Request $request): Response
    {
        return $this->redirect(Configuration::LOGIN_URL);
    }

    /**
     * Authenticates a user and processes the login request.
     *
     * This action handles user login attempts. If the login form is submitted, it attempts to authenticate the user
     * with the provided credentials. Upon successful login, the user is redirected to the admin dashboard.
     * If authentication fails, an error message is displayed on the login page.
     *
     * @return Response The response object which can either redirect on success or render the login view with
     *                  an error message on failure.
     * @throws Exception If the parameter for the URL generator is invalid throws an exception.
     */
    public function login(Request $request): Response
    {
        $logged = null;
        if ($request->hasValue('submit')) {
            $logged = $this->app->getAuthenticator()->login($request->value('username'), $request->value('password'));
            if ($logged) {
                return $this->redirect($this->url('profile.index'));
            }
        }

        $message = $logged === false ? 'Bad username or password' : null;
        return $this->html(compact("message"));
    }

    /**
     * Logs out the current user.
     *
     * This action terminates the user's session and redirects them to a view. It effectively clears any authentication
     * tokens or session data associated with the user.
     *
     * @return ViewResponse The response object that renders the logout view.
     */
    public function logout(Request $request): Response
    {
        // zruší session / identitu
        $this->app->getAuthenticator()->logout();

        // presmeruj na home/root
        return $this->redirect($this->url('home.index'));
    }

    /**
     * Zobrazí výber, či sa chce používateľ registrovať ako Hráč alebo Klub.
     */
    public function register(Request $request): Response
    {
        return $this->html(); // Zobrazí register.view.php
    }

    /**
     * Zobrazí registračný formulár pre Hráča.
     */
    public function registerPlayer(Request $request): Response
    {
        return $this->html([
            'errors' => [],
            'values' => [],
        ]); // Zobrazí registerPlayer.view.php
    }

    /**
     * Zobrazí registračný formulár pre Klub.
     */
    public function registerClub(Request $request): Response
    {
        return $this->html([
            'errors' => [],
            'values' => [],
        ]); // Zobrazí registerClub.view.php
    }


    /**
     * Spracuje registračný formulár (či už pre Hráča alebo Klub)
     */
    public function processRegistration(Request $request): Response
    {
        $errors = [];
        $email = trim((string)$request->value('email'));
        $password = (string)$request->value('password');
        $passwordConfirm = (string)$request->value('password_confirm');
        $role = (string)$request->value('role'); // 'player' alebo 'club'

        // A. Validácia spoločných polí (Email, Heslo)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Neplatný formát e-mailu.';
        } elseif (Pouzivatel::getOneByEmail($email) !== null) {
            $errors['email'] = 'Používateľ s týmto e-mailom už existuje.';
        }

        if (strlen($password) < 6) {
            $errors['password'] = 'Heslo musí mať aspoň 6 znakov.';
        } elseif ($password !== $passwordConfirm) {
            $errors['password_confirm'] = 'Heslá sa nezhodujú.';
        }

        // B. Rozšírená validácia a spracovanie (pre Hráča)
        $specificValues = [];
        if ($role === 'player') {
            // Pre zjednodušenie použijeme iba povinné polia, ktoré máš už vo validácii Hráča
            $meno = trim((string)$request->value('meno'));
            $priezvisko = trim((string)$request->value('priezvisko'));

            if ($meno === '') $errors['meno'] = 'Meno je povinné.';
            if ($priezvisko === '') $errors['priezvisko'] = 'Priezvisko je povinné.';

            $specificValues = [
                'meno' => $meno,
                'priezvisko' => $priezvisko,
                // Ostatné polia hráča (pozícia, krajina atď.) sa môžu vyplniť neskôr po prihlásení
            ];

        } elseif ($role === 'club') {
            // C. Rozšírená validácia a spracovanie (pre Klub)
            $nazov = trim((string)$request->value('nazov'));
            $region = trim((string)$request->value('region'));

            if ($nazov === '') $errors['nazov'] = 'Názov klubu je povinný.';
            if ($region === '') $errors['region'] = 'Región je povinný.';

            $specificValues = [
                'nazov' => $nazov,
                'region' => $region,
                'kontakt_email' => $email, // Klub má kontakt_email zhodný s loginom
            ];
        } else {
            $errors['role'] = 'Neplatná rola.';
        }


        // D. Ak sú chyby, vráť formulár s chybami
        if (!empty($errors)) {
            $values = array_merge([
                'email' => $email,
                'password' => $password,
                'password_confirm' => $passwordConfirm,
            ], $specificValues);

            // Vraciame do príslušného formulára
            $viewName = ($role === 'player') ? 'registerPlayer' : 'registerClub';
            return $this->html(compact('errors', 'values'), $viewName);
        }

        // E. Uloženie používateľa
        $pouzivatel = new Pouzivatel();
        $pouzivatel->setEmail($email);
        $pouzivatel->setHeslo(password_hash($password, PASSWORD_DEFAULT));
        $pouzivatel->setJeAdmin(false); // Nový používateľ nikdy nie je admin
        $pouzivatel->save();
        $pouzivatelId = $pouzivatel->getId();

        // F. Uloženie roly (Hráč/Klub)
        if ($role === 'player') {
            $hrac = new Hrac();
            $hrac->setPouzivatelId($pouzivatelId);
            $hrac->setMeno($specificValues['meno']);
            $hrac->setPriezvisko($specificValues['priezvisko']);
            // Nastavíme dátum/krajinu/pozíciu na default/null, aby sa dal Hráč uložiť a editoval neskôr
            $hrac->setDatumNarodenia(date('Y-m-d')); // Použijeme aspoň nejakú hodnotu, ak je v DB NOT NULL
            $hrac->setKrajina("Slovensko");
            $hrac->setPozicia("Nešpecifikovaná");
            $hrac->setPreferovanaNoha("obidve");
            $hrac->save();
        } elseif ($role === 'club') {
            $klub = new Klub();
            $klub->setPouzivatelId($pouzivatelId);
            $klub->setNazov($specificValues['nazov']);
            $klub->setRegion($specificValues['region']);
            $klub->setKontaktEmail($specificValues['kontakt_email']);
            $klub->setLogoCesta(null);
            $klub->setCreatedAt(date('Y-m-d H:i:s'));
            $klub->save();
        }

        // G. Prihlásenie a presmerovanie
        $this->app->getAuthenticator()->login($email, $password);

        // Presmerovanie na domovskú stránku alebo profil
        return $this->redirect($this->url('profile.index'));
    }

    public function deleteAccount(Request $request): Response
    {
        // 1. Musí byť prihlásený
        if (!$this->user->isLoggedIn()) {
            // keď nie je prihlásený, pošleme ho na home
            return $this->redirect($this->url('home.index'));
        }

        // 2. ID aktuálneho používateľa
        $userId = $this->user->getId();  // AppUser → Pouzivatel::getId()

        // 3. Zmažeme hráčov naviazaných na tohto používateľa
        $hraci = Hrac::getAll('pouzivatel_id = ?', [$userId]);
        foreach ($hraci as $hrac) {
            $hrac->delete();
        }

        // 4. Zmažeme kluby naviazané na tohto používateľa
        $kluby = Klub::getAll('pouzivatel_id = ?', [$userId]);
        foreach ($kluby as $klub) {
            $klub->delete();
        }

        // 5. Zmažeme samotného používateľa
        $pouzivatel = Pouzivatel::getOne($userId);
        if ($pouzivatel) {
            $pouzivatel->delete();
        }

        // 6. Odhlásime ho
        $this->app->getAuthenticator()->logout();

        // 7. Presmerujeme na home
        return $this->redirect($this->url('home.index'));
    }
}
