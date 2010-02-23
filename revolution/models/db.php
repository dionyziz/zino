<?php
    mysql_connect( 'localhost', 'zinolive', '7eyBYoaIHwI47p50nRLD' ) or die( mysql_error() );
    mysql_select_db( 'zinolive' );
    mysql_query( "SET NAMES UTF8;" );

    function db( $sql, $bind = false ) {
        if ( $bind == false ) {
            $bind = array();
        }
        foreach ( $bind as $key => $value ) {
            if ( is_string( $value ) ) {
                $value = addslashes( $value );
                $value = '"' . $value . '"';
            }
            else if ( is_array( $value ) ) {
                foreach ( $value as $i => $subvalue ) {
                    $value[ $i ] = addslashes( $subvalue );
                }
                $value = "(" . implode( ", ", $value ) . ")";
            }
            $bind[ ':' . $key ] = $value;
            unset( $bind[ $key ] );
        }
        $res = mysql_query( strtr( $sql, $bind ) ) or die( mysql_error() );
        return $res;
    }
?>
