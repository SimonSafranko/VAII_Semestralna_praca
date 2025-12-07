<?php
/** @var LinkGenerator $link */

use Framework\Support\LinkGenerator;

?>
<div class="text-center my-5">
    <h1>Registrácia do FUTSAL CONNECT</h1>
    <p class="lead">Vyberte si, ako sa chcete do systému registrovať:</p>

    <a href="<?= $link->url('auth.registerPlayer') ?>" class="btn btn-lg btn-primary mx-3">
        ⚽ Registrovať ako Hráč
    </a>

    <a href="<?= $link->url('auth.registerClub') ?>" class="btn btn-lg btn-success mx-3">
        🏟️ Registrovať ako Klub
    </a>

    <p class="mt-4">
        Už máš účet? <a href="<?= App\Configuration::LOGIN_URL ?>">Prihlás sa tu.</a>
    </p>
</div>