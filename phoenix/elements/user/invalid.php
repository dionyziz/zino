<?php

    class ElementUserInvalid extends Element {
        public function Render() {
            global $user;

            if ( $user->Exists() ) {
                return Redirect();
            }

            ?><p style="margin:0;padding:20px 0;">Πληκτρολόγησες έναν μη έγκυρο συνδιασμό ονόματος χρήστη και κωδικού πρόσβασης.</p><?php
        }

    }
?>
