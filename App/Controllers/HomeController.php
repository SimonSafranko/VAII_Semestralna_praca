<?php

namespace App\Controllers;

use App\Models\Hrac;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class HomeController extends BaseController
{
    public function authorize(Request $request, string $action): bool
    {
        return true;
    }

    public function index(Request $request): Response
    {
        $hraci = Hrac::getAll(
            null,
            [],
            'id DESC',
            10
        );

        return $this->html([
            'hraci' => $hraci,
        ]);
    }

    public function contact(Request $request): Response
    {
        return $this->html();
    }
}
