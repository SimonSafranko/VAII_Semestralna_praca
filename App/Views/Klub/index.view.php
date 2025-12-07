<?php
/** @var \App\Models\Klub[] $kluby */
?>

<h1>Futsalové kluby</h1>

<?php if (empty($kluby)): ?>

    <p>Momentálne nemáme v systéme žiadne kluby.</p>

<?php else: ?>

    <table class="table">
        <thead>
        <tr>
            <th>Názov</th>
            <th>Región</th>
            <th>Kontakt</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($kluby as $klub): ?>
            <tr>
                <td><?= htmlspecialchars($klub->getNazov() ?? '', ENT_QUOTES) ?></td>
                <td><?= htmlspecialchars($klub->getRegion() ?? '', ENT_QUOTES) ?></td>
                <td><?= htmlspecialchars($klub->getKontaktEmail() ?? '', ENT_QUOTES) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>
