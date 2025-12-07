<?php
/** @var array $errors */

/** @var array $values */

// Helper funkcia pre výpis hodnôt (rovnaká ako pri create)
function fieldValue(array $values, string $key): string
{
    return htmlspecialchars($values[$key] ?? '', ENT_QUOTES);
}

?>

<h1>Upraviť hráča</h1>

<?php
if (!empty($errors)): ?>
    <div class="form-errors" style="color: red">
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

<form method="post" action="?c=Hrac&a=update" id="hrac-form">

    <input type="hidden" name="id" value="<?= fieldValue($values, 'id') ?>">

    <div>
        <label for="meno">Meno *</label>
        <input type="text" id="meno" name="meno"
               required
               value="<?= fieldValue($values, 'meno') ?>">
    </div>

    <div>
        <label for="priezvisko">Priezvisko *</label>
        <input type="text" id="priezvisko" name="priezvisko"
               required
               value="<?= fieldValue($values, 'priezvisko') ?>">
    </div>

    <div>
        <label for="datum_narodenia">Dátum narodenia *</label>
        <input type="date" id="datum_narodenia" name="datum_narodenia"
               required
               value="<?= fieldValue($values, 'datum_narodenia') ?>">
    </div>

    <div>
        <label for="krajina">Krajina *</label>
        <input type="text" id="krajina" name="krajina"
               required
               value="<?= fieldValue($values, 'krajina') ?>">
    </div>

    <div>
        <label for="pozicia">Pozícia *</label>
        <input type="text" id="pozicia" name="pozicia"
               required
               value="<?= fieldValue($values, 'pozicia') ?>">
    </div>

    <div>
        <label for="preferovana_noha">Preferovaná noha</label>
        <select id="preferovana_noha" name="preferovana_noha">
            <option value="">— neznáme —</option>
            <option value="ľavá" <?= (fieldValue($values, 'preferovana_noha') === 'ľavá') ? 'selected' : '' ?>>Ľavá
            </option>
            <option value="pravá" <?= (fieldValue($values, 'preferovana_noha') === 'pravá') ? 'selected' : '' ?>>Pravá
            </option>
            <option value="obidve" <?= (fieldValue($values, 'preferovana_noha') === 'obidve') ? 'selected' : '' ?>>
                Obidve
            </option>
        </select>
    </div>

    <div>
        <label for="bio">Bio</label>
        <textarea id="bio" name="bio" rows="4"><?= fieldValue($values, 'bio') ?></textarea>
    </div>

    <button type="submit">Uložiť zmeny</button>
    <a href="?c=Hrac&a=index">Zrušiť</a>
</form>

<script>
    // Validácia (rovnaká ako pri create)
    document.getElementById('hrac-form').addEventListener('submit', function (e) {
        const meno = document.getElementById('meno').value.trim();
        const priezvisko = document.getElementById('priezvisko').value.trim();
        const datum = document.getElementById('datum_narodenia').value;

        let errors = [];

        if (meno.length < 2) {
            errors.push('Meno musí mať aspoň 2 znaky.');
        }
        if (priezvisko.length < 2) {
            errors.push('Priezvisko musí mať aspoň 2 znaky.');
        }
        if (!datum) {
            errors.push('Musíš vyplniť dátum narodenia.');
        }

        if (errors.length > 0) {
            e.preventDefault();
            alert(errors.join('\n'));
        }
    });
</script>