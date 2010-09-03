<?php
    global $settings;
    global $queries;
    
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
    function db_add_debug_data( $query, $time = 0 ) {
        global $queries;
        $queries[] = array(
            'sql' => $query,
            'time' => "$time ms"
        );
    }
    function db_get_debug_data() {
        global $queries;
        return $queries;
    }
    function db( $sql, $bind = false ) {
        global $settings;
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
        $startTime = microtime( true );
        $res = mysql_query( strtr( $sql, $bind ) );
        $queryTime = microtime( true ) - $startTime;
        if ( $settings[ 'beta' ] ) {
            db_add_debug_data( strtr( $sql, $bind ), $queryTime * 1000 );
        }
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
