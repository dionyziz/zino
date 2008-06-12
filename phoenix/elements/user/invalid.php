<?php

    function ElementUserInvalid() {
        global $user;

        if ( $user->Exists() ) {
            Redirect();
        }

        ?>Invalid username/password<?php
    }

?>
