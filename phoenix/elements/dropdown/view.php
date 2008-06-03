<?php
    function ElementDropdown( $name, array $items, $selected = '' ) {
        w_assert( is_string( $name ) );
        w_assert( preg_match( '/^[a-z][a-z0-9_-]*$/', $name ) );

        w_assert( is_array( $items ) );
        w_assert( count( $items ) > 0, '$items must contain at least one element' );

        foreach ( $items as $value => $data ) {
            w_assert( is_string( $value ) );

            if ( !is_array( $data ) ) {
                $items[ $value ] = array( 'url' => $data );
            }

            if ( isset( $items[ $value ][ 'title'] ) ) {
                w_assert( is_string( $items[ $value ][ 'title'] ) );
            }

            w_assert( is_string( $items[ $value ][ 'url' ] ) );
            w_assert( preg_match( '/^https?:\/\//', $items[ $value ][ 'url' ] ) );
        }



    }
?>
