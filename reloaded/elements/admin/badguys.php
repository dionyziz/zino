<?php
    function ElementAdminBadGuys() {
        global $user;
        
        if ( !$user->IsSysOp() ) {
            return;
        }
        
        $untrusted = Trust_GetUntrusted();
        ?>There are <?php
        echo count( $untrusted );
        ?> recent bad guys sneakin' around!<br /><br /><?php
        foreach ( $untrusted as $ip ) {
            echo htmlspecialchars( $ip );
            ?><br /><?php
        }
    }
?>
