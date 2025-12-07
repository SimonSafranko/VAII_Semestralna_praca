<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */
/** @var \Framework\Auth\AppUser $user */

$view->setLayout('auth');
?>

<div class="container my-5">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <h1 class="text-center text-danger mb-4">Zmazanie účtu</h1>

            <p>
                Ste prihlásený ako <strong><?= htmlspecialchars($user->getName(), ENT_QUOTES) ?></strong>.
            </p>

            <p>
                Zmazaním účtu sa:
            </p>
            <ul>
                <li>zmaže váš používateľský účet (login/e-mail),</li>
                <li>zmaže sa váš hráčsky profil (ak existuje),</li>
                <li>zmaže sa klubový profil, ktorý je na vás naviazaný (ak existuje).</li>
            </ul>

            <p class="text-danger">
                Táto akcia je nevratná.
            </p>

            <form method="post" action="<?= $link->url('auth.deleteAccount') ?>">
                <div class="d-flex justify-content-between mt-4">
                    <a href="<?= $link->url('profile.index') ?>" class="btn btn-secondary">
                        Zrušiť
                    </a>
                    <button type="submit" name="confirm" value="yes" class="btn btn-danger">
                        Áno, zmazať môj účet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
