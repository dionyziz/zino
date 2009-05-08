<?php
    /*
        Developer: abresas
    */

    function Event_Types() {
        // New events here!
        // EVENT_MODEL(_ATTRIBUTE)_ACTION
        return array(
            4 => 'EVENT_COMMENT_CREATED',
            19 => 'EVENT_FRIENDRELATION_CREATED',
            38 => 'EVENT_IMAGETAG_CREATED',
            39 => 'EVENT_FAVOURITE_CREATED',
            40 => 'EVENT_USER_BIRTHDAY' // not connected with any class. Triggered by script
        );
    }

    function Event_TypesByModel( $model ) {
        static $typesbymodel = array();

        if ( empty( $typesbymodel ) ) {
            $types = Event_Types();
            foreach ( $types as $typeid => $type ) {
                $split = explode( '_', $type );
                if ( !isset( $typesbymodel[ $split[ 1 ] ] ) ) {
                    $typesbymodel[ $split[ 1 ] ] = array();
                }
                $typesbymodel[ $split[ 1 ] ][] = $typeid;
            }
        }
        if ( !isset( $typesbymodel[ $model ] ) ) {
            throw New Exception( "Unknown event model $model" );
        }
        return $typesbymodel[ $model ];
    }

    function Event_ModelByType( $type ) {
        static $models = array();
        if ( empty( $models ) ) {
            $types = Event_Types();
            foreach ( $types as $key => $value ) {
                $split = explode( '_', $value );
                $models[ $key ] = $split[ 1 ];
            }
        }
        if ( !isset( $models[ $type ] ) ) {
            throw New Exception( "Unknown event type $type" );
        }
        return $models[ $type ];
    }

    $events = Event_Types();
    foreach ( $events as $key => $event ) {
        define( $event, $key );
    }
?>
