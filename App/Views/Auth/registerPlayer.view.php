<?php
/** @var array $errors */
/** @var array $values */
/** @var \Framework\Support\LinkGenerator $link */
/** @var \Framework\Support\View $view */

$view->setLayout('auth'); // Použijeme jednoduchý layout bez navigácie

function fieldValue(array $values, string $key): string {
    return htmlspecialchars($values[$key] ?? '', ENT_QUOTES);
}
?>

<div class="container my-5">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <h1 class="text-center">Registrácia Hráča</h1>

            <?php if (!empty($errors)): ?>
                <div class="form-errors alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $msg): ?>
                            <li><?= htmlspecialchars($msg) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= $link->url('auth.processRegistration') ?>">
                <input type="hidden" name="role" value="player">

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail (Login) *</label>
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
                    <label for="meno" class="form-label">Meno *</label>
                    <input name="meno" type="text" id="meno" class="form-control"
                           value="<?= fieldValue($values, 'meno') ?>" required>
                    <div class="text-danger"><?= $errors['meno'] ?? '' ?></div>
                </div>
                <div class="mb-3">
                    <label for="priezvisko" class="form-label">Priezvisko *</label>
                    <input name="priezvisko" type="text" id="priezvisko" class="form-control"
                           value="<?= fieldValue($values, 'priezvisko') ?>" required>
                    <div class="text-danger"><?= $errors['priezvisko'] ?? '' ?></div>
                </div>

                <div class="text-center mt-4">
                    <button class="btn btn-primary" type="submit">Dokončiť registráciu</button>
                </div>
            </form>

            <p class="text-center mt-3">
                Už máš účet? <a href="<?= App\Configuration::LOGIN_URL ?>">Prihlás sa.</a>
            </p>
        </div>
    </div>
</div>