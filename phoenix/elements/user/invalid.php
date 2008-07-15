<?php

    class ElementUserInvalid extends Element {
        public function Render() {
            global $user;

            if ( $user->Exists() ) {
                return Redirect();
            }

            ?><p>Πληκτρολόγησες έναν μη έγκυρο συνδιασμό ονόματος χρήστη και κωδικού πρόσβασης.</p><?php
        }

    }
?>
