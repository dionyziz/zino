<?php
	function ValidId( $x ) {
		if ( is_numeric( $x ) ) {
			$z = intval( $x );
			return ( ( $z == $x ) && ( $z > 0 ) );
		}
        return false;
	}

	function ValidURL( $url ) {
		$pattern = "/^(http|https)\:\/\/[a-zA-Z0-9_\/\?\=\-%\:\.#&]*$/";
		return ( bool )preg_match( $pattern, $url );
	}

    function ValidEmail( $email ) {
        // Partially incorrect:
        // * Will allow some invalid domain names such as domains containing same-label siblings.
        // * Won't allow the ' character in usernames, which can be valid
        //
        // If you need absolutely correct validation, use your own manual string manipulation
        //

        return ( bool )preg_match( '#^[a-z0-9%_+.-]+@([a-z0-9][a-z0-9-]{0,62}(?<!-)\.)*([a-z]{2,4}|museum)$#i', $email );
    }
?>
