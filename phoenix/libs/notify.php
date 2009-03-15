<?php

    global $libs;
    $libs->Load( 'event' );

    class NotificationFinder extends Finder {
        protected $mModel = 'Notification';

        public function FindByUser( User $user, $offset = 0, $limit = 20 ) {
            global $water;

            $query = $this->mDb->Prepare( 
                "SELECT
                    *
                FROM
                    :notify
                    RIGHT JOIN :events ON
                        `notify_eventid` = `event_id`
                WHERE
                    `notify_touserid` = :userid
                ORDER BY
                    `notify_eventid` DESC
                LIMIT
                    :offset, :limit;" );

            $query->BindTable( 'notify', 'events' );
            $query->Bind( 'userid', $user->Id );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit + 6 );
        
            $res = $query->Execute();

            $ret = array();
            $i = 0;
            while ( $row = $res->FetchArray() ) {
                if ( $i < $limit ) {
                    $notif = New Notification( $row );
                    $notif->CopyEventFrom( New Event( $row ) );
                    $ret[] = $notif;
                }
                ++$i;
            }

            return New Collection( $ret, $i );
        }
        public function DeleteByCommentAndUser( Comment $comment, User $user ) {
            $query = $this->mDb->Prepare( "
                DELETE
                FROM
                    :notify
                USING
                    :notify 
                    RIGHT JOIN :events ON
                        `notify_eventid` = `event_id`
                WHERE
                    `event_typeid` = :typeid AND
                    `event_itemid` = :commentid AND
                    `notify_touserid` = :userid
                ;" );

            $query->BindTable( 'notify', 'events' );
            $query->Bind( 'typeid', EVENT_COMMENT_CREATED );
            $query->Bind( 'commentid', $comment->Id );
            $query->Bind( 'userid', $user->Id );

            return $query->Execute()->Impact();
        }
        public function FindByComment( Comment $comment ) {
            global $water; 

            $query = $this->mDb->Prepare( "SELECT 
                        *
                    FROM
                        :notify RIGHT JOIN :events
                            ON notify_eventid = event_id
                    WHERE
                        `event_typeid` = :typeid AND
                        `event_itemid` = :commentid
                    LIMIT 
                        1;" );
            
            $query->BindTable( 'notify' );
            $query->BindTable( 'events' );
            $query->Bind( 'typeid', EVENT_COMMENT_CREATED );
            $query->Bind( 'commentid', $comment->Id );
            
            $res = $query->Execute();
            if ( $res->Results() ) {
                $row = $res->FetchArray();
                $notif = New Notification( $row );
                $notif->CopyEventFrom( New Event( $row ) );

                return $notif;
            }
            else {
                $water->Warning( "No results for comment " . $comment->Id );
            }

            return false;
        }
        public function FindByRelation( FriendRelation $relation ) {
            global $water; 

            $query = $this->mDb->Prepare( "SELECT
                        *
                    FROM
                        :notify RIGHT JOIN :events
                            ON notify_eventid = event_id
                    WHERE
                        `event_typeid` = :typeid AND
                        `event_itemid` = :relationid
                    LIMIT
                        1;" );

            $query->BindTable( 'notify' );
            $query->BindTable( 'events' );
            $query->Bind( 'typeid', EVENT_FRIENDRELATION_CREATED );
            $query->Bind( 'relationid', $relation->Id );

            $res = $query->Execute();
            if ( $res->Results() ) {
                return New Notification( $res->FetchArray() );
            }
            else {
                $water->Warning( "No results for relation " . $relation->Id );
            }
                
            return false;
        }
        public function FindByImageTags( ImageTag $tag ) {
            global $water;
        
            $query = $this->mDb->Prepare( "SELECT
                        *
                    FROM
                        :notify RIGHT JOIN :events
                            ON notify_eventid = event_id
                    WHERE
                        `event_typeid` = :typeid AND
                        `event_itemid` = :tagid
                    LIMIT
                        1;" );
             
            $query->BindTable( 'notify' );
            $query->BindTable( 'events' );
            $query->Bind( 'typeid', EVENT_IMAGETAG_CREATED );
            $query->Bind( 'tagid', $tag->Id );
            
            $res = $query->Execute();
            if ( $res->Results() ) {
                return New Notification( $res->FetchArray() );
            }
            else {
                $water->Warning( "No results for image tag " . $tag->Id );
            }
                
            return false;
        }
    }

    function Notification_FieldByEvent( $event ) {
        w_assert( $event->Typeid != 0 );

        if ( $event->Typeid == EVENT_COMMENT_CREATED ) {
            $comment = $event->Item;
            if ( $comment->Parentid == 0 ) {
                return 'reply';
            }
            switch ( Type_FromObject( $comment->Item ) ) {
                case TYPE_JOURNAL:
                    return 'journalcomment';
                case TYPE_IMAGE:
                    return 'photocomment';
                case TYPE_POLL:
                    return 'pollcomment';
                case TYPE_USERPROFILE:
                    return 'profilecomment';
            }
        }
        else if ( $event->Typeid == EVENT_FRIENDRELATION_CREATED ) {
            return 'friendaddition';
        }
        else if ( $event->Typeid == EVENT_IMAGETAG_CREATED ) {
            return 'phototag';
        }
        else if ( $event->Typeid == EVENT_FAVOURITE_CREATED ) {
            return 'favourite';
        }
        
        return false;
    }

    class Notification extends Satori {
        protected $mDbTableAlias = 'notify';

        public function __get( $key ) {
            switch ( $key ) {
                case 'Item':
                    w_assert( $this->Event->Exists(), 'Event does not exist' );

                    return $this->Event->Item;
                default:
                    return parent::__get( $key );
            }
        }
        public function CopyEventFrom( $value ) {
            $this->mRelations[ 'Event' ]->CopyFrom( $value );
        }
        public function Email() {
            global $rabbit_settings;
            global $libs;

            $libs->Load( 'rabbit/helpers/email' );
            
            switch ( $this->Event->Typeid ) {
                case EVENT_COMMENT_CREATED:
                    $target = 'notification/email/comment';
                    break;
                case EVENT_FRIENDRELATION_CREATED:
                    $target = 'notification/email/friend';
                    break;
                case EVENT_IMAGETAG_CREATED:
                    $target = 'notification/email/imagetag';
                    break;
                case EVENT_FAVOURITE_CREATED:
                    $target = 'notification/email/favourite';
                    break;
                case EVENT_USER_BIRTHDAY:
                    $target = 'notification/email/birthday';
                    break;
                default:
                    return;
            }

            ob_start();
            $subject = Element( $target, $this );
            $message = ob_get_clean();

            // send an email
            Email( $this->ToUser->Name, $this->ToUser->Profile->Email, $subject, $message, $rabbit_settings[ 'applicationname' ], 'noreply@' . $rabbit_settings[ 'hostname' ] );
        }
        public function OnBeforeCreate() {
            global $water;

            if ( $this->Touserid == $this->Fromuserid ) {
                return false;
            }

            $this->mRelations[ 'ToUser' ]->Rebuild();
            $field = Notification_FieldByEvent( $this->Event );

            if ( $field === false ) {
                return;
            }
            
            $attribute = 'Email' . $field;
            if ( $this->ToUser->Preferences->$attribute == 'yes' && !empty( $this->ToUser->Profile->Email ) && $this->ToUser->Emailverified ) {
                $this->Email();
            }
            
            $attribute = 'Notify' . $field;
            // $trace .= "Notify attribute", $attribute );
            if ( $this->ToUser->Preferences->$attribute != 'yes' ) {
                $water->Trace( "No notification for user " . $this->ToUser->Name, $this->ToUser->Preferences->$attribute );
                if ( !is_object( $this->ToUser ) ) {
                    die( "this->ToUser not an object" );
                }
                if ( !is_object( $this->ToUser->Preferences ) ) {
                    die( "prefernces not an object" );
                }
                return false;
            }
            // $water->Trace( "New notification for user " . $this->ToUser->Name, $this->ToUser->Preferences->$attribute );
            return true;
        }
        public function Relations() {
            $this->ToUser = $this->HasOne( 'User', 'Touserid' );
            $this->FromUser = $this->HasOne( 'User', 'Fromuserid' );
            $this->Event = $this->HasOne( 'Event', 'Eventid' );
        }
        public function OnBeforeUpdate() {
            throw New Exception( 'Notifications cannot be edited!' );
        }
    }

?>
