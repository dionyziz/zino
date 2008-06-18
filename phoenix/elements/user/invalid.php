<?php

    function ElementUserInvalid() {
        global $user;

        if ( $user->Exists() ) {
            Redirect();
        }

        ?>Πληκτρολόγησες έναν μη έγκυρο συνδιασμό ονόματος χρήστη και κωδικού πρόσβασης.<?php
    }

?>
