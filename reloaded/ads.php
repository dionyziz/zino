<?php 
    if ( isset( $_GET[ 'type' ] ) ) {
        $type = $_GET[ 'type' ];
    }
    else {
        $type = 'banner';
    }
    
    // google ads are not compatible with xhtml/xml strict
    // therefore we cannot use the turtle framework for serving this page
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
    <body>
        <?php
        switch ($type) {
            case 'leaderboard':
            case 'cc':
            case 'banner':
            default:
                ?><a href="http://www.zino.gr/myalbums.php" target="_top"><img src="http://static.zino.gr/images/ads/albums.jpg" alt="Albums" title="Zino Albums" /></a><?php
                break;
                /*
                ?>
                <script type="text/javascript">
                google_ad_client = "pub-9675840962794412";
                google_alternate_ad_url = "http://www.zino.gr/ads.php?type=cc";
                google_ad_width = 728;
                google_ad_height = 90;
                google_ad_format = "728x90_as";
                google_ad_type = "text";
                //2007-02-24: zino
                google_ad_channel = "2379850219";
                </script>
                <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                </script>
                <?php
                break;
                */
                
                /*
                ?>
                <script type="text/javascript">
                google_ad_client = "pub-9675840962794412";
                google_ad_width = 120;
                google_ad_height = 240;
                google_ad_format = "120x240_as";
                google_ad_type = "text_image";
                //2006-11-26: zino
                google_ad_channel = "2379850219";
                </script>
                <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                </script><?php
                */
        }
        ?>
    </body>
</html>
