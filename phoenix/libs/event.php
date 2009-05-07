<?php
    /*
        Developer: abresas
    */

    /*
    new comment

    $event = New Event();
    $event->Typeid = EVENT_COMMENT_CREATED;
    $event->Itemid = $comment->Id;
    $event->Created = $comment->Created;
    $event->Userid = $comment->Userid;
    $event->Save();
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

    class EventFinder extends Finder {
        protected $mModel = 'Event';

        public function DeleteByEntity( $entity ) {
            $query = $this->mDb->Prepare( 
                'DELETE 
                FROM
                    :events
                USING
                    :events 
                WHERE 
                    `event_itemid` = :itemid AND 
                    `event_typeid` IN :typeids;'
            );

            $query->BindTable( 'events', 'notify' );
            $query->Bind( 'itemid', $entity->Id );
            $query->Bind( 'typeids', Event_TypesByModel( strtoupper( get_class( $entity ) ) ) );

            return $query->Execute()->Impact();
        }
    }

    class Event extends Satori {
        protected $mDbTableAlias = 'events';

        public function CopyUserFrom( $value ) {
            $this->mRelations[ 'User' ]->CopyFrom( $value );
        }
        public function CopyItemFrom( $value ) {
            $this->mRelations[ 'Item' ]->CopyFrom( $value );
        }
        public function Relations() {
            global $water;
            global $libs;

            $libs->Load( 'comment' );
            $libs->Load( 'image/tag' );
            $libs->Load( 'relation/relation' );
            $libs->Load( 'favourite' );
            
            if ( $this->Exists() ) {
                $model = Event_ModelByType( $this->Typeid );
            }
            $this->User = $this->HasOne( 'User', 'Userid' );
            if ( $this->Exists() ) {
                $this->Item = $this->HasOne( $model, 'Itemid' );
            }
        }
        protected function OnCreate() {
            global $user;
            global $libs;
            
            $libs->Load( 'notify' );
            $libs->Load( 'image/tag' );

            /* notification firing */
            switch ( $this->Typeid ) {
                case EVENT_COMMENT_CREATED:
                    $comment = $this->Item;
                    $entity = $comment->Item;

                    $notif = New Notification();
                    if ( $comment->Parentid > 0 ) {
                        $notif->Touserid = $comment->Parent->Userid;
                    }
                    else {
                        switch ( get_class( $entity ) ) {
                            case 'User':
                                $notif->Touserid = $entity->Id;
                                break;
                            case 'Image':
                            case 'Journal':
                            case 'Poll':
                                $notif->Touserid = $entity->Userid;
                                break;
                        }
                    }
                    break;
                case EVENT_FRIENDRELATION_CREATED:
                    $notif = New Notification();
                    $notif->Touserid = $this->Item->Friendid;
                    break;
	        	case EVENT_IMAGETAG_CREATED:
                    $notif = New Notification();
                    $notif->Touserid = $this->Item->Personid;
                    break;
                case EVENT_FAVOURITE_CREATED:
                    $notif = New Notification();
                    $notif->Touserid = $this->Item->Item->Userid;
                    break;
                case EVENT_USER_BIRTHDAY:
                    $notif = New Notification();
                    $notif->Touserid = $this->Itemid;
                    break;
                default:
                    return; // items that don't create any notifications don't need to be saved
                    // for the rest that "break"ed, save the notification
            }
            $notif->Eventid = $this->Id;
            $notif->Fromuserid = $this->Userid;
            $notif->Typeid = $this->Typeid;
            $notif->Itemid = $this->Itemid;
            $notif->Save();
        }
        protected function LoadDefaults() {
            $this->Created = NowDate();
        }
    }

?>
