<?php
/** @var array $errors */
/** @var array $values */

function fieldValue(array $values, string $key): string {
    return htmlspecialchars($values[$key] ?? '', ENT_QUOTES);
}
?>

<h1>Upraviť klub</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $msg): ?>
                <li><?= htmlspecialchars($msg) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="?c=Klub&a=update">
    <input type="hidden" name="id" value="<?= fieldValue($values, 'id') ?>">

    <div class="mb-3">
        <label for="nazov" class="form-label">Názov klubu *</label>
        <input type="text" id="nazov" name="nazov" class="form-control"
               required value="<?= fieldValue($values, 'nazov') ?>">
        <div class="text-danger"><?= $errors['nazov'] ?? '' ?></div>
    </div>

    <div class="mb-3">
        <label for="region" class="form-label">Región / Mesto *</label>
        <input type="text" id="region" name="region" class="form-control"
               required value="<?= fieldValue($values, 'region') ?>">
        <div class="text-danger"><?= $errors['region'] ?? '' ?></div>
    </div>

    <div class="mb-3">
        <label for="kontakt_email" class="form-label">Kontaktný e-mail *</label>
        <input type="email" id="kontakt_email" name="kontakt_email" class="form-control"
               required value="<?= fieldValue($values, 'kontakt_email') ?>">
        <div class="text-danger"><?= $errors['kontakt_email'] ?? '' ?></div>
    </div>

    <div class="mb-3">
        <label for="logo_cesta" class="form-label">Logo (cesta k súboru)</label>
        <input type="text" id="logo_cesta" name="logo_cesta" class="form-control"
               value="<?= fieldValue($values, 'logo_cesta') ?>">
    </div>

    <button type="submit" class="btn btn-success">Uložiť zmeny</button>
    <a href="?c=Profile&a=index" class="btn btn-secondary">Späť na profil</a>
</form>

<script>
    // jednoduchá JS validácia na klientovi
    document.querySelector('form').addEventListener('submit', function (e) {
        const nazov = document.getElementById('nazov').value.trim();
        const region = document.getElementById('region').value.trim();
        const email = document.getElementById('kontakt_email').value.trim();

        let errors = [];

        if (nazov.length < 2) {
            errors.push('Názov klubu musí mať aspoň 2 znaky.');
        }
        if (region.length < 2) {
            errors.push('Región musí mať aspoň 2 znaky.');
        }
        if (!email.includes('@')) {
            errors.push('Zadaj platný e-mail.');
        }

        if (errors.length > 0) {
            e.preventDefault();
            alert(errors.join('\n'));
        }
    });
</script>
