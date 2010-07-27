<?php

    define( 'OPERATION_CREATE', 1 );
    define( 'OPERATION_READ', 2 );
    define( 'OPERATION_UPDATE', 3 );
    define( 'OPERATION_DELETE', 4 );

    function Type_Get() {
        return array(
            1 => array( 'TYPE_POLL', 'Poll' ),
            2 => array( 'TYPE_PHOTO', 'Image' ),
            3 => array( 'TYPE_USERPROFILE', 'User' ),
            4 => array( 'TYPE_JOURNAL', 'Journal' ),
            5 => array( 'TYPE_COMMENT', 'Comment' ),
            6 => array( 'TYPE_SHOUT', 'Shout' ),
            7 => array( 'TYPE_SCHOOL', 'School' ),
			8 => array( 'TYPE_STOREITEM', 'Storeitem' ),
        );
    }

    function Type_Prepare() {
        $types = Type_Get();
        foreach ( $types as $key => $value ) {
            define( $value[ 0 ], $key );
        }
    }

    function Type_FromObject( $object ) {
        $types = Type_Get();
        $class = get_class( $object );
        foreach ( $types as $key => $value ) {
            if ( $value[ 1 ] == $class ) {
                return $key;
            }
        }
        throw New Exception( "Invalid object class on Type_FromObject" );
    }

    function Type_GetClass( $typeid ) {
        $types = Type_Get();
        if ( !isset( $types[ $typeid ] ) ) {
            throw New Exception( "Invalid typeid $typeid on Type_GetClass" );
        }
        return $types[ $typeid ][ 1 ];
    }

    Type_Prepare();

?>
