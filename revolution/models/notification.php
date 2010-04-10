<?php
    /*
        Developer: abresas, ted
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
    function Event_TypeByModel( $model ) {
        $rtypes = array_flip( Event_Types() );
        if ( isset( $rtypes[ $model ] ) ) {
            return $rtypes[ $model ];
        }
        return false;
    }
    function Event_ModelByType( $typeid ) {
        $types = Event_Types();
        if ( isset( $types[ $typeid ] ) ) {
            return $types[ $typeid ];
        }
        return false;
    }
    class Notification {
        public function Create( $fromuserid, $touserid, $eventmodel, $itemid ){
            $fromuserid = ( int )$fromuserid;
            $touserid = ( int )$touserid;
            $typeid = Event_TypeByModel( $eventmodel );
            db( "INSERT INTO `notify`
                    (`notify_fromuserid`, `notify_touserid`, `notify_created`, `notify_typeid`, `notify_itemid`)
                VALUES
                    (:fromuserid, :touserid, NOW(), :typeid, :itemid)", compact( 'fromuserid', 'touserid', 'eventid', 'typeid', 'itemid' )
            );
            $id = mysql_insert_id();
        }
    }
?>
