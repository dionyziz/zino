<?php
    define( 'PI', 3.141592 );
    define( 'CAPTCHA_MIN_A', 0          );
    define( 'CAPTCHA_MAX_A', 1.2        );
    define( 'CAPTCHA_MIN_B', 0          );
    define( 'CAPTCHA_MAX_B', 2 * PI     );
    define( 'CAPTCHA_MIN_C', 0          );
    define( 'CAPTCHA_MAX_C', 0.1        );
    define( 'CAPTCHA_DETAIL_LEVEL', 25  );
    define( 'CAPTCHA_WIDTH', 200        );
    define( 'CAPTCHA_HEIGHT', 120       );
    define( 'CAPTCHA_MIN_ANGLE', -10    );
    define( 'CAPTCHA_MAX_ANGLE',  10    );
    define( 'CAPTCHA_BOTTOM_FACTOR',  10);
    define( 'CAPTCHA_BOTTOM_OFFSET', 100);
    
    if ( !function_exists( 'imageconvolution' ) ) {
        function imageconvolution( $src, $filter, $filter_div, $offset ) {
            if ( $src == null ) {
                return 0;
            }
           
            $sx = imagesx( $src );
            $sy = imagesy( $src );
            $srcback = ImageCreateTrueColor( $sx, $sy );
            ImageCopy( $srcback, $src, 0, 0, 0, 0, $sx, $sy );
           
            if( $srcback == null ){
                return 0;
            }
               
            // FIX HERE
            // $pxl array was the problem so simply set it with very low values
            $pxl = array( 1, 1 );
            // this little fix worked for me as the undefined array threw out errors

            for ( $y = 0; $y < $sy; ++$y ) {
                for ( $x = 0; $x < $sx; ++$x ) {
                    $new_r = $new_g = $new_b = 0;
                    $alpha = imagecolorat( $srcback, $pxl[ 0 ], $pxl[ 1 ] );
                    $new_a = $alpha >> 24;
                   
                    for ( $j = 0; $j < 3; ++$j ) {
                        $yv = min( max( $y - 1 + $j, 0 ), $sy - 1 );
                        for ( $i = 0; $i < 3; ++$i ) {
                            $pxl = array( min( max( $x - 1 + $i, 0 ), $sx - 1 ), $yv );
                            $rgb = imagecolorat( $srcback, $pxl[ 0 ], $pxl[ 1 ] );
                            $new_r += ( ( $rgb >> 16 ) & 0xFF) * $filter[ $j ][ $i ];
                            $new_g += ( ( $rgb >> 8 ) & 0xFF) * $filter[ $j ][ $i ];
                            $new_b += ( $rgb & 0xFF) * $filter[ $j ][ $i ];
                        }
                    }

                    $new_r = ( $new_r / $filter_div ) + $offset;
                    $new_g = ( $new_g / $filter_div ) + $offset;
                    $new_b = ( $new_b / $filter_div ) + $offset;

                    $new_r = ( $new_r > 255 )? 255 : ( ( $new_r < 0 )? 0: $new_r );
                    $new_g = ( $new_g > 255 )? 255 : ( ( $new_g < 0 )? 0: $new_g );
                    $new_b = ( $new_b > 255 )? 255 : ( ( $new_b < 0 )? 0: $new_b );

                    $new_pxl = ImageColorAllocateAlpha( $src, ( int )$new_r, ( int )$new_g, ( int )$new_b, $new_a );
                    if ( $new_pxl == -1 ) {
                        $new_pxl = ImageColorClosestAlpha($src, ( int )$new_r, ( int )$new_g, ( int )$new_b, $new_a );
                    }
                    if ( ( $y >= 0 ) && ( $y < $sy ) ) {
                        imagesetpixel( $src, $x, $y, $new_pxl );
                    }
                }
            }
            imagedestroy( $srcback );
            return 1;
        }
    }

    function Captcha_GenerateFunction() {
        // generate 'em
        $func = array();
        for ( $i = 0; $i < CAPTCHA_DETAIL_LEVEL; ++$i ) { // number of combined sine functions
            $func[] = array(
                Captcha_Random( CAPTCHA_MIN_A, CAPTCHA_MAX_A ),
                Captcha_Random( CAPTCHA_MIN_B, CAPTCHA_MAX_B ),
                Captcha_Random( CAPTCHA_MIN_C, CAPTCHA_MAX_C )
            );
        }
        return $func;
    }
    
    function Captcha_Random( $min, $max ) {
        // generate a random float between inclusive $min and non-inclusive $max
        return $min + ( $max - $min ) * rand( 0, 32768 ) / 32768; // max random range for windows system (let's stay within the bounds)
    }
    
    function Captcha_CallFunction( $func, $x ) { // make a distortion curve function
        $y = 0;
        foreach ( $func as $component ) {
            $y += $component[ 0 ] * sin( $component[ 1 ] + $component[ 2 ] * $x );
        }
        return $y;
    }
    
    function Captcha_Image( $text ) {
        global $rabbit_settings;
        global $water;
        
        $water->Profile( 'CAPTCHA generation' );
        
        $func1 = Captcha_GenerateFunction();
        $func2 = Captcha_GenerateFunction();
        
        // draw it
        $im1 = imagecreatetruecolor( CAPTCHA_WIDTH, CAPTCHA_HEIGHT ); // temporary surface
        $white = imagecolorallocate( $im1, 255, 255, 255 );
        $forecolor = imagecolorallocate( $im1, rand( 0, 200 ), rand( 0, 200 ), rand( 0, 200 ) );

        imagefill( $im1, 0, 0, $white );
        imagettftext( $im1, rand( 14, 16 ), rand( CAPTCHA_MIN_ANGLE, CAPTCHA_MAX_ANGLE ), 5, 50, $forecolor, $rabbit_settings[ 'rootdir' ] . '/bin/resources/fonts/arial.ttf', $text );
        
        $im2 = imagecreatetruecolor( CAPTCHA_WIDTH, CAPTCHA_HEIGHT ); // final surface
        $white = imagecolorallocate( $im1, 255, 255, 255 );

        imagefill( $im2, 0, 0, $white );

        for ( $x = 0; $x < CAPTCHA_WIDTH; ++$x ) {
            $beginy = Captcha_CallFunction( $func1, $x );
            $bottomy = Captcha_CallFunction( $func2, $x );
            if ( $bottomy < 0 ) {
                $bottomy = 0;
            }
            $endy   = $beginy + CAPTCHA_BOTTOM_OFFSET + CAPTCHA_BOTTOM_FACTOR * $bottomy;
            for ( $y = 0; $y < CAPTCHA_HEIGHT; ++$y ) {
                $rgb = imagecolorat( $im1, $x, $y );
                $r = ( $rgb >> 16 ) & 0xff;
                $g = ( $rgb >> 8  ) & 0xff;
                $b = $rgb & 0xff;
                $color = imagecolorallocate( $im2, $r, $g, $b );
                imagesetpixel( $im2, $x, $beginy + ( $endy - $beginy ) * $y / CAPTCHA_HEIGHT, $color );
            }
        }
        
        $blur = array(
            array( 1.0, 2.0, 1.0 ), 
            array( 2.0, 4.0, 2.0 ), 
            array( 1.0, 2.0, 1.0 )
        );
        
        imageconvolution( $im2, $blur, 16, 0 );
        
        ob_start();
        imagepng( $im2 );

        $water->ProfileEnd();

        return ob_get_clean();
    }
?>
