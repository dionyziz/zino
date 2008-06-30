<?php
    global $libs;

    $libs->Load( 'sanitizer' );

    function WYSIWYG_PreProcess( $html ) {
        global $rabbit_settings;

        $html = preg_replace(
            '#\<object [^>]++\>\s*\<param [^>]*?value\="http\://www\.youtube\.com/v/([a-zA-Z0-9_-]+)"[^>]*+\>.*?\</object\>#i',
            '<img src="' . $rabbit_settings[ 'imagesurl' ] . 'video-placeholder.png?v=$1" />',
            $html
        );
        $html = preg_replace(
            '#\<embed\s*+src\="http\://www\.veoh\.com\/videodetails2\.swf\?permalinkId\=([a-zA-Z0-9_\-]+)[^"]++"[^>]++\>\</embed\>#i',
            '<img src="' . $rabbit_settings[ 'imageurl' ] . 'video-placeholder.png?w=$1" />',
            $html
        );

        return $html;
    }

    function WYSIWYG_PostProcess( $html ) {
        global $xhtmlsanitizer_goodtags, $rabbit_settings;

        // YouTube support
        $html = preg_replace( 
            '#\<img\s*src\=(["\']?)' 
            . preg_quote( $rabbit_settings[ 'imagesurl' ], "#" )
            . 'video-placeholder\.png\?v\=([a-zA-Z0-9_-]+)\1[^>]*/?\>#i', 
            '<object width="425" height="344"><param name="movie" value="http://www.youtube.com/v/\2"></param><embed src="http://www.youtube.com/v/\2" type="application/x-shockwave-flash" width="425" height="344"></embed></object>', 
            $html
        );

        $sanitizer = New XHTMLSanitizer();

        foreach ( $xhtmlsanitizer_goodtags as $tag => $attributes ) {
            if ( $tag == '' ) {
                continue;
            }

            $goodtag = New XHTMLSaneTag( $tag );
            if ( is_array( $attributes ) ) {
                foreach ( $attributes as $attribute => $true ) {
                    $goodtag->AllowAttribute( New XHTMLSaneAttribute( $attribute ) );
                }
            }
            foreach ( $xhtmlsanitizer_goodtags[ '' ] as $attribute => $true ) {
                $goodtag->AllowAttribute( New XHTMLSaneAttribute( $attribute ) );
            }
            $sanitizer->AllowTag( $goodtag );
        }
        $sanitizer->SetSource( $html );

        return $sanitizer->GetXHTML();
    }
?>
