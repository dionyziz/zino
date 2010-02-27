<?php

    /* Generic */

    function Page_OutputXMLHead( $stylesheet ) {
        echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        echo "<?xml-stylesheet type=\"text/xsl\" href=\"$stylesheet\"?>";
    }

    /* Resource (+Social) */

    function Page_OutputSocialResource( $resource, $method, $vars ) {
        $stylesheet = Page_ResourceStylesheet( $resource, $method );

        Page_OutputXMLHead( $stylesheet );
        Page_OutputSocialStart();
        Resource_Call( $resource, $method, $vars );
        Page_OutputSocialEnd();
    }

    function Page_ResourceStylesheet( $resource, $method ) {
        global $settings;

        return $settings[ 'base' ] . "/xslt/" . $resource . "/" . $method . ".xsl";
    }

    /* Social */
    
    function Page_OutputSocialStart() {
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
    
    function Page_OutputSocialEnd() {
        ?></social><?php
    }

?>
