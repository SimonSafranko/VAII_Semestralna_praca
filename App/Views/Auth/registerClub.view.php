<?php
/** @var array $errors */

/** @var array $values */
/** @var LinkGenerator $link */

/** @var View $view */

use Framework\Support\LinkGenerator;
use Framework\Support\View;

$view->setLayout('auth');

function fieldValue(array $values, string $key): string
{
    return htmlspecialchars($values[$key] ?? '', ENT_QUOTES);
}

?>

<div class="container my-5">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <h1 class="text-center">Registrácia Klubu</h1>

            <?php
            if (!empty($errors)): ?>
                <div class="form-errors alert alert-danger">
                    <ul>
                        <?php
                        foreach ($errors as $msg): ?>
                            <li><?= htmlspecialchars($msg) ?></li>
                        <?php
                        endforeach; ?>
                    </ul>
                </div>
            <?php
            endif; ?>

            <form method="post" action="<?= $link->url('auth.processRegistration') ?>">
                <input type="hidden" name="role" value="club">

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail (Login/Kontakt) *</label>
                    <input name="email" type="email" id="email" class="form-control"
                           value="<?= fieldValue($values, 'email') ?>" required>
                    <div class="text-danger"><?= $errors['email'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Heslo *</label>
                    <input name="password" type="password" id="password" class="form-control" required>
                    <div class="text-danger"><?= $errors['password'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="password_confirm" class="form-label">Potvrdenie hesla *</label>
                    <input name="password_confirm" type="password" id="password_confirm" class="form-control" required>
                    <div class="text-danger"><?= $errors['password_confirm'] ?? '' ?></div>
                </div>

                <hr>

                <div class="mb-3">
                    <label for="nazov" class="form-label">Názov Futsalového Klubu *</label>
                    <input name="nazov" type="text" id="nazov" class="form-control"
                           value="<?= fieldValue($values, 'nazov') ?>" required>
                    <div class="text-danger"><?= $errors['nazov'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="region" class="form-label">Región / Mesto *</label>
                    <input name="region" type="text" id="region" class="form-control"
                           value="<?= fieldValue($values, 'region') ?>" required>
                    <div class="text-danger"><?= $errors['region'] ?? '' ?></div>
                </div>

                <div class="text-center mt-4">
                    <button class="btn btn-success" type="submit">Dokončiť registráciu</button>
                </div>
            </form>

            <p class="text-center mt-3">
                Už máš účet? <a href="<?= App\Configuration::LOGIN_URL ?>">Prihlás sa.</a>
            </p>
        </div>
    </div>
</div>