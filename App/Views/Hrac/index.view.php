<?php
/** @var \App\Models\Hrac[] $hraci */
?>

<h1>Hráči</h1>

<p>
    <a href="?c=Hrac&a=create">+ Pridať hráča</a>
</p>

<input type="text" id="search" placeholder="Hľadať podľa mena alebo krajiny" />

<table id="players-table" border="1" cellpadding="5">
    <thead>
    <tr>
        <th>Meno</th>
        <th>Priezvisko</th>
        <th>Krajina</th>
        <th>Pozícia</th>
        <th>Akcie</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($hraci as $hrac): ?>
        <tr>
            <td class="player-name">
                <?= htmlspecialchars($hrac->getMeno()) ?>
            </td>
            <td><?= htmlspecialchars($hrac->getPriezvisko()) ?></td>
            <td class="player-country"><?= htmlspecialchars($hrac->getKrajina()) ?></td>
            <td><?= htmlspecialchars($hrac->getPozicia()) ?></td>
            <td>
                <a href="?c=Hrac&a=edit&id=<?= $hrac->getId() ?>">Upraviť</a>
                |
                <a href="?c=Hrac&a=delete&id=<?= $hrac->getId() ?>"
                   onclick="return confirm('Naozaj chceš zmazať tohto hráča?');">
                    Zmazať
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<script>
    // „netriviálny“ JS: live filtrovanie tabuľky podľa mena/krajiny
    document.getElementById('search').addEventListener('input', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#players-table tbody tr');

        rows.forEach(row => {
            const name = row.querySelector('.player-name').textContent.toLowerCase();
            const country = row.querySelector('.player-country').textContent.toLowerCase();
            row.style.display = (name.includes(filter) || country.includes(filter)) ? '' : 'none';
        });
    });
</script>
