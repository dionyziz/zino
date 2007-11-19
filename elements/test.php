<?php
    function ElementTest() {
        error_reporting( E_ALL );
        
        header( 'Content-type: text/plain' );
        
        $sanitizer = New XHTMLSanitizer();
        $sanitizer->SetSource( 'Hello <img src="something.jpg" alt="hello" /> world </b>' );
        var_dump( $sanitizer->GetXHTML() );
    }
?>
