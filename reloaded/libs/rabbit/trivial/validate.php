<?php
	function ValidId( $x ) {
		if ( is_numeric( $x ) ) {
			$z = intval( $x );
			return ( ( $z == $x ) && ( $z > 0 ) );
		}
        return false;
	}

	function ValidURL( $url ) {
		$pattern = "/^[a-zA-Z0-9_\/\?\=\-%\:\.#]$/";
		if ( !preg_match( $pattern, $url ) ) {
			return true;
		}
		return false;
	}
?>
