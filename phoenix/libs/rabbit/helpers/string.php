<?php
    global $water;
    
    if ( !function_exists( 'mb_substr' ) ) {
        $water->Notice( 'The multibyte extension is not available; you might not be able to utf8_substr() properly! ' );
    }
    
	function utf8_substr( $string, $start, $len ) {
		return mb_substr( $string, $start, $len, 'UTF-8' );
	}
    function utf8_strtolower( $inputString ) {
        $outputString = utf8_decode( $inputString );
        $outputString = strtolower( $outputString );
        $outputString = utf8_encode( $outputString );

        return $outputString;
    }
    function utf8_strtoupper( $inputString ) {
        $outputString = utf8_decode( $inputString );
        $outputString = strtoupper( $outputString );
        $outputString = utf8_encode( $outputString );

        return $outputString;
    }
    function utf8_ucfirst( $inputString ) {
        $outputString = utf8_decode( $inputString );
        $outputString = ucfirst( $outputString );
        $outputString = utf8_encode( $outputString );

        return $outputString;
    }
	function myescape( $str ) {
		return mysql_escape_string( $str );
	}
?>
