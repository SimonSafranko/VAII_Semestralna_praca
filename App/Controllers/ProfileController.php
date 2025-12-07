<?php

namespace App\Controllers;

use App\Models\Hrac;
use App\Models\Klub;
use App\Models\Pouzivatel;
use App\Configuration;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class ProfileController extends BaseController
{
    // Sem môžu len prihlásení
    public function authorize(Request $request, string $action): bool
    {
        return $this->user->isLoggedIn();
    }

    public function index(Request $request): Response
    {
        /** @var Pouzivatel|null $identity */
        $identity = $this->user->getIdentity();

        if (!$identity) {
            // Pre istotu – fallback
            return $this->redirect(Configuration::LOGIN_URL);
        }

        $userId = $identity->getId();

        // Skús nájsť Hráča naviazaného na pouzivatel_id
        $hraci = Hrac::getAll('pouzivatel_id = ?', [$userId]);
        $hrac = $hraci[0] ?? null;

        // Skús nájsť Klub naviazaný na pouzivatel_id
        $kluby = Klub::getAll('pouzivatel_id = ?', [$userId]);
        $klub = $kluby[0] ?? null;

        return $this->html([
            'pouzivatel' => $identity,
            'hrac' => $hrac,
            'klub' => $klub,
        ]);
    }
}
