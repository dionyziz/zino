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
        // * Will allow some invalid domain names such as domains starting with a -, or
        //   domains containing same-label siblings.
        // * Won't allow the ' character in usernames, which can be valid
        //
        // If you need absolutely correct validation, use your own manual string manipulation
        //

        return ( bool )preg_match( '#^[a-z.0-9%_+-]+@([a-z0-9_-]{1,63}\.)*([a-z]{2,4}|museum)$#i', $email );
    }
?>
