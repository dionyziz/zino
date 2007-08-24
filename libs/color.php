<?php    
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
