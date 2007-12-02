<?php
    function ActionImageMyAlbums() {
    	global $user;

    	if ( $user->IsAnonymous() ) {
            return Redirect( '?p=register' );
    	}	
        return Redirect( 'user/' . $user->Username() . '?viewalbums=yes' );
    }
?>
