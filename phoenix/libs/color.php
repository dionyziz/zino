<?php    
    function imagegradient( $image, $startx, $starty, $endx, $endy, array $startcolor, array $endcolor ) {
        assert( is_resource( $image ) );
        assert( is_int( $startx ) );
        assert( is_int( $starty ) );
        assert( is_int( $endx ) );
        assert( is_int( $endy ) );
        assert( count( $startcolor ) == 3 );
        assert( count( $endcolor ) == 3 );
        assert( isset( $startcolor[ 0 ] ) );
        assert( isset( $startcolor[ 1 ] ) );
        assert( isset( $startcolor[ 2 ] ) );
        assert( isset( $endcolor[ 0 ] ) );
        assert( isset( $endcolor[ 1 ] ) );
        assert( isset( $endcolor[ 2 ] ) );
        assert( $startx === $endx || $starty === $endy );
        assert( $endx - $startx + $endy - $starty != 0 );
        $toptobottom = $startx === $endx;
        if ( $toptobottom ) {
            $width = imagesx( $image );
            if ( $starty < $endy ) {
                $start = $starty;
                $end = $endy;
            }
            else {
                $start = $endy;
                $end = $starty;
            }
        }
        else {
            $height = imagesy( $image );
            if ( $startx < $endx ) {
                $start = $startx;
                $end = $endx;
            }
            else {
                $start = $endx;
                $end = $startx;
            }
        }
        for ( $i = $start; $i < $end; ++$i ) {
            $percentage = ($i - $start) / ( $end - $start );
            $color = imagecolorallocate( $image, 
                round( Tween( $startcolor[ 0 ], $endcolor[ 0 ], $percentage ) ),
                round( Tween( $startcolor[ 1 ], $endcolor[ 1 ], $percentage ) ),
                round( Tween( $startcolor[ 2 ], $endcolor[ 2 ], $percentage ) )
            );
            if ( $toptobottom ) {
                imageline( $image, 0, $i, $width, $i, $color );
            }
            else {
                imageline( $image, $i, 0, $i, $height, $color );
            }
        }
    }

    function Color_Encode( $r, $g, $b ) {
        w_assert( is_int( $r ) );
        w_assert( is_int( $g ) );
        w_assert( is_int( $b ) );
        w_assert( $r >= 0 );
        w_assert( $g >= 0 );
        w_assert( $b >= 0 );
        w_assert( $r <= 255 );
        w_assert( $g <= 255 );
        w_assert( $b <= 255 );
        
        return $r << 16 | $g << 8 | $b;
    }
    
    function Color_Decode( $color ) {
        w_assert( $color >= 0 );
        
        $b = $color & 255;
        $color = $color >> 8;
        $g = $color & 255;
        $color = $color >> 8;
        $r = $color & 255;
        $color = $color >> 8;
        if ( $color > 0 ) {
            return false; // invalid color
        }
        return array( $r, $g, $b );
    }
?>
