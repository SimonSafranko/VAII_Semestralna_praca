<?php
/** @var Pouzivatel $pouzivatel */

/** @var Hrac|null $hrac */
/** @var Klub|null $klub */
/** @var LinkGenerator $link */

use App\Models\Hrac;
use App\Models\Klub;
use App\Models\Pouzivatel;
use Framework\Support\LinkGenerator;

?>

<h1>Môj profil</h1>

<p>
    <strong>Prihlásený ako:</strong>
    <?= htmlspecialchars($pouzivatel->getEmail() ?? '', ENT_QUOTES) ?>
</p>

<?php
if ($hrac): ?>

    <h2>Hráčsky profil</h2>
    <ul>
        <li>Meno: <?= htmlspecialchars($hrac->getMeno() ?? '', ENT_QUOTES) ?></li>
        <li>Priezvisko: <?= htmlspecialchars($hrac->getPriezvisko() ?? '', ENT_QUOTES) ?></li>
        <li>Krajina: <?= htmlspecialchars($hrac->getKrajina() ?? '', ENT_QUOTES) ?></li>
        <li>Pozícia: <?= htmlspecialchars($hrac->getPozicia() ?? '', ENT_QUOTES) ?></li>
        <li>Preferovaná noha: <?= htmlspecialchars($hrac->getPreferovanaNoha() ?? '', ENT_QUOTES) ?></li>
        <li>Bio: <?= nl2br(htmlspecialchars($hrac->getBio() ?? '', ENT_QUOTES)) ?></li>
    </ul>

    <p>
        <a href="?c=Hrac&a=edit&id=<?= $hrac->getId() ?>">Upraviť hráčsky profil</a>
    </p>

<?php
elseif ($klub): ?>

    <h2>Klubový profil</h2>
    <ul>
        <li>Názov: <?= htmlspecialchars($klub->getNazov() ?? '', ENT_QUOTES) ?></li>
        <li>Región: <?= htmlspecialchars($klub->getRegion() ?? '', ENT_QUOTES) ?></li>
        <li>Kontakt e-mail: <?= htmlspecialchars($klub->getKontaktEmail() ?? '', ENT_QUOTES) ?></li>
    </ul>

    <p>
        <a href="?c=Klub&a=edit&id=<?= $klub->getId() ?>">Upraviť klub</a>
    </p>

<?php
else: ?>

    <p>Nemáš ešte vyplnený hráčsky ani klubový profil.</p>

<?php
endif; ?>

<hr class="my-4">

<div class="d-flex justify-content-between align-items-center">
    <span class="text-muted">
        Ak nechceš ďalej využívať FUTSAL CONNECT, môžeš svoj účet natrvalo zmazať.
    </span>

    <a href="<?= $link->url('auth.deleteAccount') ?>"
       class="btn btn-outline-danger"
       onclick="return confirm('Naozaj chceš natrvalo zmazať svoj účet? Táto akcia je nevratná.');">
        Zmazať môj účet
    </a>
</div>
