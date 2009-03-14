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
            1 => 'EVENT_ALBUM_CREATED',
            2 => 'EVENT_ALBUM_UPDATED', // not in use
            3 => 'EVENT_ALBUM_DELETED', // not in use
            4 => 'EVENT_COMMENT_CREATED',
            5 => 'EVENT_COMMENT_UPDATED', // not in use
            6 => 'EVENT_COMMENT_DELETED', // not in use
            7 => 'EVENT_IMAGE_CREATED',
            8 => 'EVENT_IMAGE_UPDATED', // not in use
            9 => 'EVENT_IMAGE_DELETED', // not in use
            10 => 'EVENT_JOURNAL_CREATED',
            11 => 'EVENT_JOURNAL_UPDATED',
            12 => 'EVENT_JOURNAL_DELETED', // not in use
            13 => 'EVENT_POLL_CREATED',
            14 => 'EVENT_POLL_UPDATED', // not in use
            15 => 'EVENT_POLL_DELETED', // not in use
            16 => 'EVENT_POLLVOTE_CREATED', // not in use
            17 => 'EVENT_POLLOPTION_CREATED', // not in use
            18 => 'EVENT_POLLOPTION_DELETED', // not in use
            19 => 'EVENT_FRIENDRELATION_CREATED',
            20 => 'EVENT_FRIENDRELATION_UPDATED',
            // 21 => 'EVENT_USERSPACE_UPDATED', // removed but reserved
            22 => 'EVENT_USERPROFILE_UPDATED', // not in use
            23 => 'EVENT_USERPROFILE_VISITED', // not in use
            24 => 'EVENT_USERPROFILE_EDUCATION_UPDATED',
            25 => 'EVENT_USERPROFILE_SEXUALORIENTATION_UPDATED',
            26 => 'EVENT_USERPROFILE_RELIGION_UPDATED',
            27 => 'EVENT_USERPROFILE_POLITICS_UPDATED',
            28 => 'EVENT_USERPROFILE_SMOKER_UPDATED',
            29 => 'EVENT_USERPROFILE_DRINKER_UPDATED',
            30 => 'EVENT_USERPROFILE_ABOUTME_UPDATED',
            31 => 'EVENT_USERPROFILE_MOOD_UPDATED',
            32 => 'EVENT_USERPROFILE_LOCATION_UPDATED',
            33 => 'EVENT_USERPROFILE_HEIGHT_UPDATED',
            34 => 'EVENT_USERPROFILE_WEIGHT_UPDATED',
            35 => 'EVENT_USERPROFILE_HAIRCOLOR_UPDATED',
            36 => 'EVENT_USERPROFILE_EYECOLOR_UPDATED',
            37 => 'EVENT_USER_CREATED',
            38 => 'EVENT_IMAGETAG_CREATED',
            39 => 'EVENT_FAVOURITE_CREATED',
            40 => 'EVENT_USER_BIRTHDAY'
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
            $query = $this->mDb->Prepare( '
                DELETE 
                FROM 
                    :events 
                WHERE 
                    `event_itemid` = :itemid AND 
                    `event_typeid` IN :typeids;'
            );

            $query->BindTable( 'events' );
            $query->Bind( 'itemid', $entity->Id );
            $query->Bind( 'typeids', Event_TypesByModel( strtoupper( get_class( $entity ) ) ) );

            return $query->Execute()->Impact();
        }
        public function FindLatest( $offset = 0, $limit = 20 ) {
            $query = $this->mDb->Prepare(
                'SELECT
                    *
                FROM
                    :events 
                    LEFT JOIN :users ON 
                        `event_userid` = `user_id`
                    LEFT JOIN :images ON
                        `user_avatarid` = `image_id`
                WHERE
                    `event_typeid` != :commentevent AND
                    `event_typeid` != :relationevent
                ORDER BY
                    `event_id` DESC
                LIMIT
                    :offset, :limit;'
            );

            $query->BindTable( 'events', 'users', 'images' );
            $query->Bind( 'commentevent', EVENT_COMMENT_CREATED );
            $query->Bind( 'relationevent', EVENT_FRIENDRELATION_CREATED );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );

            $res = $query->Execute();
            $bymodel = array();
            while ( $row = $res->FetchArray() ) {
                $event = New Event( $row );
                $user = New User( $row );
                $user->CopyAvatarFrom( New Image( $row ) );
                $event->CopyUserFrom( $user );
                $bymodel[ Event_ModelByType( $event->Typeid ) ][] = $event;
            }

            $ret = array(); // sorted by eventid, ASC
            foreach ( $bymodel as $model => $events ) {
                $events = $this->FindItemsByModel( $model, $events );
                foreach ( $events as $event ) {
                    $ret[ $event->Id ] = $event;
                }
            }

            krsort( $ret );

            return $ret;
        }
        public function FindItemsByModel( $model, $events ) {
            global $libs;
            $libs->Load( 'school/school' );
            $libs->Load( 'place' );
            $libs->Load( 'mood' );

            $eventsByItemid = array();
            while ( $event = array_shift( $events ) ) {
                $eventsByItemid[ $event->Itemid ][] = $event;
            }

            $obj = New $model();
            $table = $obj->DbTable->Alias;
            $field = $obj->PrimaryKeyFields[ 0 ];

            if ( strtolower( $model ) != 'userprofile' ) {
                $query = $this->mDb->Prepare( '
                    SELECT
                        *
                    FROM
                        :' . $table . '
                    WHERE
                        `' . $field . '` IN :itemids
                    ' );

                $query->BindTable( $table );
            }
            else {
                $query = $this->mDb->Prepare( '
                    SELECT
                        *
                    FROM
                        :userprofiles
                        LEFT JOIN :schools ON 
                            `profile_schoolid` = `school_id`
                        LEFT JOIN :places ON
                            `profile_placeid` = `place_id`
                        LEFT JOIN :moods ON
                            `profile_moodid` = `mood_id`
                    WHERE
                        `profile_userid` IN :itemids
                ' );

                $query->BindTable( 'userprofiles', 'schools', 'places', 'moods' );
            }
            
            $query->Bind( 'itemids', array_keys( $eventsByItemid ) );

            $res = $query->Execute();
            $ret = array();
            while ( $row = $res->FetchArray() ) {
                $events = $eventsByItemid[ $row[ $field ] ];
                foreach ( $events as $event ) {
                    if ( strtolower( $model ) != 'userprofile' ) {
                        $obj = New $model( $row );
                    }
                    else {
                        $obj = New UserProfile( $row );
                        $obj->CopySchoolFrom( New School( $row ) );
                        $obj->CopyLocationFrom( New Place( $row ) );
                        $obj->CopyMoodFrom( New Mood( $row ) );
                    }
                    $event->CopyItemFrom( $obj );
                    $ret[] = $event;
                }
            }

            return $ret;
        }
        public function FindByUser( $user, $offset = 0, $limit = 1000, $order = array( 'Id', 'DESC' ) ) {
            $prototype = New Event();
            $prototype->Userid = $user->Id;
            return $this->FindByPrototype( $prototype, $offset, $limit, $order );
        }
        public function FindByType( $typeids, $offset = 0, $limit = 1000, $order = 'DESC' ) {
            if ( !is_array( $typeids ) ) {
                $typeids = array( $typeids );
            }

            w_assert( $order == 'DESC' || $order == 'ASC', "Only 'ASC' or 'DESC' values are allowed in the order" );

            $query = $this->mDb->Prepare(
                'SELECT
                    *
                FROM
                    :events
                WHERE
                    `event_typeid` IN :types
                ORDER BY
                    `event_id` ' . $order . '
                LIMIT 
                    :offset, :limit;'
            );
            $query->BindTable( 'events' );
            $query->Bind( 'types', $typeids );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );

            return $this->FindBySQLResource( $query->Execute() );
        }
        public function FindByUserAndType( $user, $typeids, $offset = 0, $limit = 1000, $order = array( 'Id', 'DESC' ) ) {
            $prototype = New Event();
            $prototype->Userid = $user->Id;
            $prototype->Typeid = $typeids;
            return $this->FindByPrototype( $prototype, $offset, $limit, $order );
        }
        public function DeleteByUserAndType( $user, $typeid ) {
            $query = $this->mDb->Prepare( '
                DELETE FROM
                    :events
                WHERE
                    `event_userid` = :userid AND
                    `event_typeid` = :typeid
                ;' );

            $query->BindTable( 'events' );
            $query->Bind( 'userid', $user->Id );
            $query->Bind( 'typeid', $typeid );

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
                    $notif->Touserid = $this->Item->Id;
                    break;
                default:
                    return; // items that don't create any notifications don't need to be saved
                    // for the rest that "break"ed, save the notification
            }
            $notif->Eventid = $this->Id;
            $notif->Fromuserid = $this->Userid;
            $notif->Save();
        }
        protected function OnBeforeUpdate() {
            throw New Exception( 'Events cannot be updated' );

            return false;
        }
        public function LoadDefaults() {
            $this->Created = NowDate();
        }
    }

?>
