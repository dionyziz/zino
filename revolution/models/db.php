<?php
    global $settings;
    
    mysql_connect( $settings[ 'db' ][ 'host' ], $settings[ 'db' ][ 'user' ], $settings[ 'db' ][ 'password' ] ) or die( mysql_error() );
    mysql_select_db( $settings[ 'db' ][ 'name' ] );
    mysql_query( "SET NAMES UTF8;" );

    class DBException extends Exception {}

    function db_debug( $sql, $bind = false ) {
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
        return strtr( $sql, $bind );
    }
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
        $res = mysql_query( strtr( $sql, $bind ) );
        if ( $res === false ) {
            throw new DBException( mysql_error() );
        }
        return $res;
    }
    function db_array( $sql, $bind = false, $id_column = false ) {
        $res = db( $sql, $bind );
        $rows = array();
        if ( $id_column !== false ) {
            while ( $row = mysql_fetch_array( $res ) ) {
                $rows[ $row[ $id_column ] ] = $row;
            }
        }
        else {
            while ( $row = mysql_fetch_array( $res ) ) {
                $rows[] = $row;
            }
        }
        return $rows;
    }
?>
