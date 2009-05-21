<?php

    class ElementUserInvalid extends Element {
        public function Render() {
            global $user;

            if ( $user->Exists() ) {
                return Redirect();
            }

            ?><p style="margin-bottom:0;padding-bottom:20px;">Πληκτρολόγησες έναν μη έγκυρο συνδιασμό ονόματος χρήστη και κωδικού πρόσβασης.</p><?php
        }

    }
?>
