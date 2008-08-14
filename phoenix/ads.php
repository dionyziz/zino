<?php 
    require_once 'libs/rabbit/rabbit.php';

    Rabbit_Construct( 'plain' );

    if ( isset( $_GET[ 'type' ] ) ) {
        $type = $_GET[ 'type' ];
    }
    else {
        $type = AD_JOURNAL;
    }
    
    // google ads are not compatible with xhtml/xml strict
    // it has to be a stand-alone page served as text/html
    // imported as an XHTML object
    header("Content-type: text/html;charset=utf-8"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
    <head>
        <title>Ads</title>
        <style type="text/css">
            body {
                margin: 0;
                padding: 0;
            }
            a img {
                border-width: 0;
            }
        </style>
    </head>
    <body><?php
        switch ( $type ) {
            case AD_JOURNAL:
                ?><script type="text/javascript"><!--
                google_ad_client = "pub-6131563030489305";
                /* 728x90, created 8/14/08 */
                google_ad_slot = "5223999939";
                google_ad_width = 728;
                google_ad_height = 90;
                //-->
                </script>
                <script type="text/javascript"
                src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                </script><?php
        }
    ?></body>
</html><?php
    Rabbit_Destruct();
?>
