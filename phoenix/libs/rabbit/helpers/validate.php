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
		return preg_match( $pattern, $url );
	}
?>
