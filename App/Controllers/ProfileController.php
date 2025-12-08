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
    public function authorize(Request $request, string $action): bool
    {
        return $this->user->isLoggedIn();
    }

    public function index(Request $request): Response
    {
        /** @var Pouzivatel|null $identity */
        $identity = $this->user->getIdentity();

        if (!$identity) {
            return $this->redirect(Configuration::LOGIN_URL);
        }

        $userId = $identity->getId();

        $hraci = Hrac::getAll('pouzivatel_id = ?', [$userId]);
        $hrac = $hraci[0] ?? null;

        $kluby = Klub::getAll('pouzivatel_id = ?', [$userId]);
        $klub = $kluby[0] ?? null;

        return $this->html([
            'pouzivatel' => $identity,
            'hrac' => $hrac,
            'klub' => $klub,
        ]);
    }
}
