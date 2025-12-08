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

class AuthController extends BaseController
{
    public function index(Request $request): Response
    {
        return $this->redirect(Configuration::LOGIN_URL);
    }

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
        return $this->html(compact('message'));
    }

    public function logout(Request $request): Response
    {
        $this->app->getAuthenticator()->logout();

        return $this->redirect($this->url('home.index'));
    }

    public function register(Request $request): Response
    {
        return $this->html();
    }

    public function registerPlayer(Request $request): Response
    {
        return $this->html([
            'errors' => [],
            'values' => [],
        ]);
    }

    public function registerClub(Request $request): Response
    {
        return $this->html([
            'errors' => [],
            'values' => [],
        ]);
    }

    public function processRegistration(Request $request): Response
    {
        $errors = [];
        $email = trim((string)$request->value('email'));
        $password = (string)$request->value('password');
        $passwordConfirm = (string)$request->value('password_confirm');
        $role = (string)$request->value('role');

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

        $specificValues = [];
        if ($role === 'player') {
            $meno = trim((string)$request->value('meno'));
            $priezvisko = trim((string)$request->value('priezvisko'));

            if ($meno === '') {
                $errors['meno'] = 'Meno je povinné.';
            }
            if ($priezvisko === '') {
                $errors['priezvisko'] = 'Priezvisko je povinné.';
            }

            $specificValues = [
                'meno' => $meno,
                'priezvisko' => $priezvisko,
            ];
        } elseif ($role === 'club') {
            $nazov = trim((string)$request->value('nazov'));
            $region = trim((string)$request->value('region'));

            if ($nazov === '') {
                $errors['nazov'] = 'Názov klubu je povinný.';
            }
            if ($region === '') {
                $errors['region'] = 'Región je povinný.';
            }

            $specificValues = [
                'nazov' => $nazov,
                'region' => $region,
                'kontakt_email' => $email,
            ];
        } else {
            $errors['role'] = 'Neplatná rola.';
        }

        if (!empty($errors)) {
            $values = array_merge([
                'email' => $email,
                'password' => $password,
                'password_confirm' => $passwordConfirm,
            ], $specificValues);

            $viewName = ($role === 'player') ? 'registerPlayer' : 'registerClub';
            return $this->html(compact('errors', 'values'), $viewName);
        }

        $pouzivatel = new Pouzivatel();
        $pouzivatel->setEmail($email);
        $pouzivatel->setHeslo(password_hash($password, PASSWORD_DEFAULT));
        $pouzivatel->setJeAdmin(false);
        $pouzivatel->save();
        $pouzivatelId = $pouzivatel->getId();

        if ($role === 'player') {
            $hrac = new Hrac();
            $hrac->setPouzivatelId($pouzivatelId);
            $hrac->setMeno($specificValues['meno']);
            $hrac->setPriezvisko($specificValues['priezvisko']);
            $hrac->setDatumNarodenia(date('Y-m-d'));
            $hrac->setKrajina('Slovensko');
            $hrac->setPozicia('Nešpecifikovaná');
            $hrac->setPreferovanaNoha('obidve');
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

        $this->app->getAuthenticator()->login($email, $password);

        return $this->redirect($this->url('profile.index'));
    }

    public function deleteAccount(Request $request): Response
    {
        if (!$this->user->isLoggedIn()) {
            return $this->redirect($this->url('home.index'));
        }

        $userId = $this->user->getId();

        $hraci = Hrac::getAll('pouzivatel_id = ?', [$userId]);
        foreach ($hraci as $hrac) {
            $hrac->delete();
        }

        $kluby = Klub::getAll('pouzivatel_id = ?', [$userId]);
        foreach ($kluby as $klub) {
            $klub->delete();
        }

        $pouzivatel = Pouzivatel::getOne($userId);
        if ($pouzivatel) {
            $pouzivatel->delete();
        }

        $this->app->getAuthenticator()->logout();

        return $this->redirect($this->url('home.index'));
    }
}
