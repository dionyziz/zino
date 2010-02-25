<?php
    global $water;
    
    if ( !function_exists( 'mb_substr' ) ) {
        $water->Notice( 'The multibyte extension is not available; you might not be able to use some of Rabbit\'s functionality properly! ' );
    }
?>
