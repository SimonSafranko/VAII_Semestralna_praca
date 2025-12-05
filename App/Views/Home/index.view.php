<?php
/** @var \App\Models\Hrac[] $hraci */
?>

    <h1>Vitajte na portáli FUTSAL CONNECT!</h1>

    <p class="lead">
        Tvoj nový nástroj pre správu futsalovej kariéry a hľadanie talentov.
    </p>

    <div class="row mt-4">
        <div class="col-md-6">
            <h3>Pre Hráčov</h3>
            <p>
                Vytvor si detailný profil (fotografie, kariérne údaje, štatistiky).
                Komunikuj s klubmi a prijímaj žiadosti o záujem (try-out/hosťovanie/prestup).
            </p>
            <a href="<?= $link->url('Hrac.create') ?>" class="btn btn-success">Registrovať sa ako hráč</a>
        </div>
        <div class="col-md-6">
            <h3>Pre Kluby</h3>
            <p>
                Filtruj a vyhľadávaj hráčov z celého sveta, posielaj im žiadosti a spravuj profil klubu.
                Uľahčujeme administratívu, aby si sa mohol sústrediť na hru.
            </p>
            <a href="<?= App\Configuration::LOGIN_URL ?>" class="btn btn-info text-white">Prihlásiť sa ako klub</a>
        </div>
    </div>

    <hr class="my-5">

    <h2>🔥 Najnovší registrovaní hráči</h2>

<?php if (!empty($hraci)): ?>
    <div class="row">
        <?php for ($i = 0; $i < min(3, count($hraci)); $i++): ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($hraci[$i]->getMeno() . ' ' . $hraci[$i]->getPriezvisko()) ?></h5>
                        <p class="card-text">
                            Pozícia: **<?= htmlspecialchars($hraci[$i]->getPozicia()) ?>**<br>
                            Krajina: <?= htmlspecialchars($hraci[$i]->getKrajina()) ?>
                        </p>
                        <a href="<?= $link->url('Hrac.index') ?>" class="btn btn-sm btn-outline-primary">Zobraziť všetkých</a>
                    </div>
                </div>
            </div>
        <?php endfor; ?>
    </div>
<?php else: ?>
    <p>Aktuálne nemáme žiadnych registrovaných hráčov. Buď prvý!</p>
<?php endif; ?>