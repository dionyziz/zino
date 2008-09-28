<?php
    function ActionImageMyAlbums() {
    	global $user;
    	global $settings;

    	if ( $user->IsAnonymous() ) {
            return Redirect( '?p=register' );
    	}	
        return Redirect( 'user/' . $user->Username() . '?viewalbums=yes' );
    }
?>
