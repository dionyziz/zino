<?php
    function ElementAdminBadGuys() {
        global $user;
        
        if ( !$user->IsSysOp() ) {
            return;
        }
        
        $untrusted = Trust_GetUntrusted();
        ?>There are <?php
        echo count( $untrusted );
        ?> recent bad guys sneakin' around!<br /><br /><ul><?php
        foreach ( $untrusted as $ip ) {
            ?><li><?php
            echo htmlspecialchars( $ip );
            ?></li><?php
        }
        ?></ul><?php
    }
?>
