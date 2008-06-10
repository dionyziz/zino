<?php
    global $water;
    
    if ( !function_exists( 'mb_substr' ) ) {
        $water->Notice( 'The multibyte extension is not available; you might not be able to utf8_substr() properly! ' );
    }
    
	function utf8_substr( $string, $start, $len ) {
		return mb_substr( $string, $start, $len, 'UTF-8' );
	}
    function utf8_strtolower( $string ) {
        return mb_convert_case( $string, MB_CASE_LOWER, 'UTF-8' );
    }
    function utf8_strtoupper( $string ) {
        return mb_convert_case( $string, MB_CASE_UPPER, 'UTF-8' );
    }
    function utf8_ucfirst( $string ) {
        return mb_convert_case( utf8_substr( $string, 0, 1 ), MB_CASE_UPPER ) . mb_convert_case( utf8_substr( $string, 1, mb_strlen( $string ) - 1 ), MB_CASE_LOWER );
    }
	function myescape( $str ) {
		return mysql_escape_string( $str );
	}
?>
