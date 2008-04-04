<?php
	function magicquotes_off() {
        $_GET    = transcribe( $_GET  );
        $_POST   = transcribe( $_POST );
        $_FILES  = transcribe( $_FILES );
        $_COOKIE = transcribe( $_COOKIE );
	}
    
    function transcribe( $aList, $aIsTopLevel = true ) {
        // from http://gr2.php.net/manual/en/function.get-magic-quotes-gpc.php#49612
        
        $gpcList = array();
        $isMagic = get_magic_quotes_gpc();
       
        foreach ( $aList as $key => $value ) {
            if ( is_array( $value ) ) {
                $decodedKey = $isMagic && !$aIsTopLevel? stripslashes( $key ): $key;
                $decodedValue = transcribe( $value, false );
            }
            else {
                $decodedKey = stripslashes( $key );
                $decodedValue = $isMagic? stripslashes( $value ): $value ;
            }
            $gpcList[ $decodedKey ] = $decodedValue;
        }
        
        return $gpcList;
    }
    
?>
