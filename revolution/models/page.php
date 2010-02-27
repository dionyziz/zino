<?php

    /* Generic */

    function Page_XMLHead( $stylesheet ) {
        echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        echo "<?xml-stylesheet type=\"text/xsl\" href=\"$stylesheet\"?>";
    }

    /* Social */
    
    function SocialPage_Start() {
        global $settings;

        ?><social generated="<?php
        echo date( "Y-m-d H:i:s", time() );
        ?>"<?php
        if ( isset( $_SESSION[ 'user' ] ) ) {
            ?> for="<?php
            echo $_SESSION[ 'user' ][ 'name' ];
            ?>"<?php
        }
        ?> generator="<?php
        echo $settings[ 'base' ];
        ?>"><?php
    }
    
    function SocialPage_End() {
        ?></social><?php
    }

?>
