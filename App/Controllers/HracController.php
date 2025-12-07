<?php

namespace App\Controllers;

use Framework\Core\BaseController;
use Framework\Http\Responses\Response;
use Framework\Http\Request;
use App\Models\Hrac;

class HracController extends BaseController
{
    /**
     * Zoznam hráčov
     */
    public function index(Request $request): Response
    {
        $hraci = Hrac::getAll();
        return $this->html(['hraci' => $hraci]);
    }

    /**
     * Formulár na vytvorenie
     */
    public function create(): Response
    {
        return $this->html([
            'errors' => [],
            'values' => [],
        ]);
    }

    /**
     * OPRAVA 1: Validácia musí prijímať $request ako parameter,
     * a používať ho priamo, nie cez $this->request().
     */
    private function validateHracForm(Request $request): array
    {
        $errors = [];

        // Používame $request->value(), nie $this->request()->value()
        $meno = trim((string)$request->value('meno'));
        $priezvisko = trim((string)$request->value('priezvisko'));
        $datumNarodenia = (string)$request->value('datum_narodenia');
        $krajina = trim((string)$request->value('krajina'));
        $pozicia = trim((string)$request->value('pozicia'));
        $preferovanaNoha = trim((string)$request->value('preferovana_noha'));

        if ($meno === '') {
            $errors['meno'] = 'Meno je povinné.';
        }
        if ($priezvisko === '') {
            $errors['priezvisko'] = 'Priezvisko je povinné.';
        }
        if ($datumNarodenia === '') {
            $errors['datum_narodenia'] = 'Dátum narodenia je povinný.';
        }
        if ($krajina === '') {
            $errors['krajina'] = 'Krajina je povinná.';
        }
        if ($pozicia === '') {
            $errors['pozicia'] = 'Pozícia je povinná.';
        }
        if ($preferovanaNoha !== '' && !in_array($preferovanaNoha, ['ľavá', 'pravá', 'obidve'], true)) {
            $errors['preferovana_noha'] = 'Neplatná preferovaná noha.';
        }

        return $errors;
    }

    /**
     * OPRAVA 2: Metóda store musí prijať Request $request
     */
    public function store(Request $request): Response
    {
        // Posielame $request do validátora
        $errors = $this->validateHracForm($request);

        $values = [
            'meno' => $request->value('meno'),
            'priezvisko' => $request->value('priezvisko'),
            'datum_narodenia' => $request->value('datum_narodenia'),
            'krajina' => $request->value('krajina'),
            'pozicia' => $request->value('pozicia'),
            'preferovana_noha' => $request->value('preferovana_noha'),
            'bio' => $request->value('bio'),
        ];

        if ($errors) {
            return $this->html([
                'errors' => $errors,
                'values' => $values,
            ]);
        }

        $hrac = new Hrac();
        $hrac->setMeno((string)$values['meno']);
        $hrac->setPriezvisko((string)$values['priezvisko']);
        $hrac->setDatumNarodenia((string)$values['datum_narodenia']);
        $hrac->setKrajina((string)$values['krajina']);
        $hrac->setPozicia((string)$values['pozicia']);
        $hrac->setPreferovanaNoha((string)$values['preferovana_noha']);
        $hrac->setBio($values['bio'] ?: null);

        $hrac->save();

        return $this->redirect($this->url('index'));
    }

    /**
     * OPRAVA 3: Metóda edit potrebuje ID z requestu
     */
    public function edit(Request $request): Response
    {
        $id = (int)$request->value('id'); // $request namiesto $this->request()
        $hrac = Hrac::getOne($id);

        if ($hrac === null) {
            return $this->redirect($this->url('index'));
        }

        $values = [
            'id' => $id,
            'meno' => $hrac->getMeno(),
            'priezvisko' => $hrac->getPriezvisko(),
            'datum_narodenia' => $hrac->getDatumNarodenia(),
            'krajina' => $hrac->getKrajina(),
            'pozicia' => $hrac->getPozicia(),
            'preferovana_noha' => $hrac->getPreferovanaNoha(),
            'bio' => $hrac->getBio(),
        ];

        return $this->html([
            'errors' => [],
            'values' => $values,
        ]);
    }

    /**
     * OPRAVA 4: Metóda update potrebuje Request $request
     */
    public function update(Request $request): Response
    {
        $id = (int)$request->value('id');
        $hrac = Hrac::getOne($id);

        if ($hrac === null) {
            return $this->redirect($this->url('index'));
        }

        // Znova posielame request do validátora
        $errors = $this->validateHracForm($request);

        $values = [
            'id' => $id,
            'meno' => $request->value('meno'),
            'priezvisko' => $request->value('priezvisko'),
            'datum_narodenia' => $request->value('datum_narodenia'),
            'krajina' => $request->value('krajina'),
            'pozicia' => $request->value('pozicia'),
            'preferovana_noha' => $request->value('preferovana_noha'),
            'bio' => $request->value('bio'),
        ];

        if ($errors) {
            return $this->html([
                'errors' => $errors,
                'values' => $values,
            ]);
        }

        $hrac->setMeno((string)$values['meno']);
        $hrac->setPriezvisko((string)$values['priezvisko']);
        $hrac->setDatumNarodenia((string)$values['datum_narodenia']);
        $hrac->setKrajina((string)$values['krajina']);
        $hrac->setPozicia((string)$values['pozicia']);
        $hrac->setPreferovanaNoha((string)$values['preferovana_noha']);
        $hrac->setBio($values['bio'] ?: null);

        $hrac->save();

        return $this->redirect($this->url('index'));
    }

    /**
     * OPRAVA 5: Delete tiež potrebuje Request pre získanie ID
     */
    public function delete(Request $request): Response
    {
        $id = (int)$request->value('id');
        $hrac = Hrac::getOne($id);

        if ($hrac !== null) {
            $hrac->delete();
        }

        return $this->redirect($this->url('index'));
    }

    public function authorize(Request $request, string $action): bool
    {
        // verejné: zoznam hráčov
        if (in_array($action, ['index'])) {
            return true;
        }

        // na všetko ostatné treba login
        if (!$this->user->isLoggedIn()) {
            return false;
        }

        // admin môže všetko
        if ($this->user->isAdmin()) {
            return true;
        }

        // hráč môže upravovať len sám seba
        if (in_array($action, ['edit', 'update', 'delete'])) {
            $id = (int)$request->value('id');
            $hrac = Hrac::getOne($id);

            if ($hrac && $hrac->getPouzivatelId() === $this->user->getId()) {
                return true;
            }

            return false;
        }

        return false;
    }
}