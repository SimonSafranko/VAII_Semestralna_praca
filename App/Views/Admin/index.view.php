<?php

/** @var LinkGenerator $link */

/** @var AppUser $user */

use Auth\AppUser;
use Framework\Support\LinkGenerator;

?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div>
                Welcome, <strong><?= $user->getName() ?></strong>!<br><br>
                This part of the application is accessible only after logging in.
            </div>
        </div>
    </div>
</div>