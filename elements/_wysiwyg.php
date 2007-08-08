<?php
    function ElementWYSIWYG( $name , $text = '' , $width = '100%' , $height = '300px' ) {
        global $page;
        
        w_assert( preg_match( '#^[a-z][a-z0-9]*$#' , $name ) );
        
        $page->AttachScript( 'js/wysiwyg.js', 'javascript', true, '' );
        
        // whitespace (space character after opening div) intentional in the following line
        ?><div style="display:none"> <?php
        echo htmlspecialchars( $text );
        ?></div><?php
        // no whitespace intentional at this point
        ?><iframe src="about:blank" onload="WYSIWYG.Create(this, '<?php
        echo $name;
        ?>')" style="width:<?php
        echo $width;
        ?>;height:<?php
        echo $height;
        ?>;border:1px solid black" frameborder="no" id="wsiskeleton"></iframe><?php
    }
?>
