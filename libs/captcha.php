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
        $func1 = Captcha_GenerateFunction();
        $func2 = Captcha_GenerateFunction();
        
        // draw it
        $im1 = imagecreatetruecolor( CAPTCHA_WIDTH, CAPTCHA_HEIGHT ); // temporary surface
        $white = imagecolorallocate( $im1, 255, 255, 255 );
        $forecolor = imagecolorallocate( $im1, rand( 0, 200 ), rand( 0, 200 ), rand( 0, 200 ) );

        imagefill( $im1, 0, 0, $white );
        imagettftext( $im1, rand( 14, 16 ), rand( CAPTCHA_MIN_ANGLE, CAPTCHA_MAX_ANGLE ), 5, 50, $forecolor, 'arial.ttf', $text );
        
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
        return ob_get_clean();
    }
?>
