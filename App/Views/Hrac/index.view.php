<?php
/** @var Hrac[] $hraci */

use App\Models\Hrac;

?>

<h1>Hráči</h1>

<label for="search">Vyhľadať hráča:</label>
<input type="search" id="search" placeholder="Hľadať podľa mena alebo krajiny">

<table id="players-table" class="players-table">
    <thead>
    <tr>
        <th>Meno</th>
        <th>Priezvisko</th>
        <th>Krajina</th>
        <th>Pozícia</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($hraci as $hrac): ?>
        <tr>
            <td class="player-name" data-label="Meno:">
                <?= htmlspecialchars($hrac->getMeno()) ?>
            </td>
            <td data-label="Priezvisko:"><?= htmlspecialchars($hrac->getPriezvisko()) ?></td>
            <td class="player-country" data-label="Krajina:"><?= htmlspecialchars($hrac->getKrajina()) ?></td>
            <td data-label="Pozícia:"><?= htmlspecialchars($hrac->getPozicia()) ?></td>
        </tr>
    <?php
    endforeach; ?>
    </tbody>
</table>
