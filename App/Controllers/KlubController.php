<?php

namespace App\Controllers;

use App\Models\Klub;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class KlubController extends BaseController
{
    /**
     * Len prihlásení môžu riešiť klub.
     */
    public function authorize(Request $request, string $action): bool
    {
        // zoznam klubov môže vidieť každý
        if ($action === 'index') {
            return true;
        }

        // na všetko ostatné musí byť prihlásený
        if (!$this->user->isLoggedIn()) {
            return false;
        }

        // admin môže všetko
        if ($this->user->isAdmin()) {
            return true;
        }

        // klub môže upravovať len svoj vlastný záznam
        if (in_array($action, ['edit', 'update'], true)) {
            $id = (int)$request->value('id');
            $klub = Klub::getOne($id);

            if ($klub && $klub->getPouzivatelId() === $this->user->getId()) {
                return true;
            }

            return false;
        }

        // iné akcie – defaultne zakázať
        return false;
    }

    /**
     * Zoznam klubov (nemusíš veľmi riešiť, ale hodí sa na test).
     */
    public function index(Request $request): Response
    {
        $kluby = Klub::getAll();
        return $this->html(['kluby' => $kluby]);
    }

    /**
     * Formulár na úpravu klubu.
     */
    public function edit(Request $request): Response
    {
        $id = (int)$request->value('id');
        $klub = Klub::getOne($id);

        if ($klub === null) {
            return $this->redirect($this->url('Klub.index'));
        }

        $values = [
            'id' => $klub->getId(),
            'nazov' => $klub->getNazov(),
            'region' => $klub->getRegion(),
            'kontakt_email' => $klub->getKontaktEmail(),
            'logo_cesta' => $klub->getLogoCesta(),
        ];

        return $this->html([
            'errors' => [],
            'values' => $values,
        ]);
    }

    /**
     * Validácia formulára pre klub.
     */
    private function validateKlubForm(Request $request): array
    {
        $errors = [];

        $nazov = trim((string)$request->value('nazov'));
        $region = trim((string)$request->value('region'));
        $kontaktEmail = trim((string)$request->value('kontakt_email'));

        if ($nazov === '') {
            $errors['nazov'] = 'Názov klubu je povinný.';
        }

        if ($region === '') {
            $errors['region'] = 'Región je povinný.';
        }

        if (!filter_var($kontaktEmail, FILTER_VALIDATE_EMAIL)) {
            $errors['kontakt_email'] = 'Zadaj platný kontaktný e-mail.';
        }

        return $errors;
    }

    /**
     * Spracovanie úpravy klubu.
     */
    public function update(Request $request): Response
    {
        $id = (int)$request->value('id');
        $klub = Klub::getOne($id);

        if ($klub === null) {
            return $this->redirect($this->url('Klub.index'));
        }

        $errors = $this->validateKlubForm($request);

        $values = [
            'id' => $id,
            'nazov' => (string)$request->value('nazov'),
            'region' => (string)$request->value('region'),
            'kontakt_email' => (string)$request->value('kontakt_email'),
            'logo_cesta' => (string)$request->value('logo_cesta'),
        ];

        if (!empty($errors)) {
            return $this->html([
                'errors' => $errors,
                'values' => $values,
            ], 'edit');
        }

        $klub->setNazov($values['nazov']);
        $klub->setRegion($values['region']);
        $klub->setKontaktEmail($values['kontakt_email']);
        $klub->setLogoCesta($values['logo_cesta'] ?: null);

        $klub->save();

        // po uložení môžeš ísť na profil
        return $this->redirect($this->url('profile.index'));
    }
}
