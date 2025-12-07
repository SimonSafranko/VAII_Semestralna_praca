<?php

/** @var string $contentHTML */

/** @var AppUser $user */

/** @var LinkGenerator $link */

use Auth\AppUser;
use Framework\Support\LinkGenerator;

?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <title>FUTSAL CONNECT | <?= App\Configuration::APP_NAME ?></title>

    <!-- Jeden favicon – FUTSAL CONNECT logo -->
    <link rel="icon" type="image/png"
          href="<?= $link->asset('images/futsal_connect_logo.png') ?>">
    <link rel="shortcut icon"
          href="<?= $link->asset('images/futsal_connect_logo.png') ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?= $link->asset('css/styl.css') ?>">
    <script src="<?= $link->asset('js/script.js') ?>"></script>
</head>

<body>
<nav class="navbar navbar-expand-lg bg-light sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= $link->url('home.index') ?>">
            <img src="<?= $link->asset('images/futsal_connect_logo.png') ?>"
                 title="FUTSAL CONNECT - Futsalová Agentúra"
                 alt="Futsal Connect Logo"
                 style="height: 40px; margin-right: 10px;">
            FUTSAL CONNECT
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $link->url('Hrac.index') ?>">Hráči</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $link->url('Klub.index') ?>">Kluby</a>
                </li>
                <?php
                if ($user->isLoggedIn()) { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $link->url('Ziadosť.index') ?>">Žiadosti</a>
                    </li>
                    <?php
                    if ($user->isAdmin()) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $link->url('Admin.index') ?>">Admin</a>
                        </li>
                        <?php
                    } ?>
                    <?php
                } ?>

                <li class="nav-item">
                    <a class="nav-link" href="<?= $link->url('home.contact') ?>">Kontakt</a>
                </li>

            </ul>
            <?php
            if ($user->isLoggedIn()) { ?>
                <span class="navbar-text me-3">
        Prihlásený ako: <b><?= $user->getName() ?></b>
    </span>

                <ul class="navbar-nav">
                    <li class="nav-item me-2">
                        <a class="nav-link btn btn-outline-primary"
                           href="<?= $link->url('profile.index') ?>">
                            Môj profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-danger"
                           href="<?= $link->url('auth.logout') ?>">
                            Odhlásiť
                        </a>
                    </li>
                </ul>

                <?php
            } else { ?>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white"
                           href="<?= App\Configuration::LOGIN_URL ?>">
                            Prihlásenie / Registrácia
                        </a>
                    </li>
                </ul>
                <?php
            } ?>
        </div>
    </div>
</nav>

<div class="container-fluid mt-3">
    <div class="web-content">
        <?= $contentHTML ?>
    </div>
</div>

<footer class="text-center py-3 mt-4 bg-light">
    <p>&copy; <?= date('Y') ?> FUTSAL CONNECT. Semestrálna práca VAII. Vypracoval: Šimon Šafranko</p>
</footer>
</body>
</html>