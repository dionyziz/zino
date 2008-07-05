<?php
	
	function ElementUserSubdomain( $theuser ) {
	    if ( !is_object( $theuser ) ) {
            return;
        }
        if ( !( $theuser instanceof User ) ) {
            return;
        }
        if ( $theuser->Subdomain != '' ) {
            echo htmlspecialchars( $theuser->Subdomain );
        }
	}
?>
