<?php
    function ElementAdPlaintext() {
        global $user;
        global $rabbit_settings;
        
        if ( $user->Exists() ) {
            ?><a href="user/<?php
            echo $user->Username();
            ?>?viewingalbums=yes">Ανέβασε τις φωτογραφίες σου στο <?php
            echo $rabbit_settings[ 'applicationname' ];
            ?></a>!<?php
        }
        else {
            ?><a href="register">Φτιάξε το δικό σου προφίλ στο <?php
            echo $rabbit_settings[ 'applicationname' ];
            ?></a>!<?php
        }
    }
?>
