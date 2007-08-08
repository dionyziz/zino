<?php
    global $water;
    
    if ( !function_exists( 'mb_substr' ) ) {
        $water->Notice( 'The multibyte extension is not available; you might not be able to utf8_substr() properly! ' );
    }
    
	function utf8_substr( $string, $start, $len ) {
		return mb_substr( $string, $start, $len, 'UTF-8' );
	}
	function mystrtolower( $str ) {
        return strtolower( $str );
	}
	function mystrtoupper( $str ) {
        return strtoupper( $str );
	}
	function myucfirst( $str ) {
		return ucfirst( $str );
	}
	function myescape( $str ) {
		return mysql_escape_string( $str );
	}

?>
