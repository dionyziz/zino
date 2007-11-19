<?php
    function ElementsDeveloperDionyzizSanitize( tString $in ) {
        global $libs;
        
        $libs->Load( 'sanitizer' );
        
        $in = $in->Get();
        
        ?>Input: <?php
        
        ob_start();
        var_dump( $in );
        echo htmlspecialchars( ob_get_clean() );
        
        ?><br/><br/>
        Output: <?php
        
        $xhtml = New XHTMLSanitizer();
        $xhtml->SetSource( $in );
        $out = $xhtml->GetXHTML();
        
        ob_start();
        var_dump( $out );
        echo htmlspecialchars( ob_get_clean() );
    }
?>
