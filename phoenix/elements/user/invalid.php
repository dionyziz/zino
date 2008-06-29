<?php

    function ElementUserInvalid() {
        global $user;

        if ( $user->Exists() ) {
            Redirect();
        }

        ?><p>Πληκτρολόγησες έναν μη έγκυρο συνδιασμό ονόματος χρήστη και κωδικού πρόσβασης.</p><?php
    }

?>
