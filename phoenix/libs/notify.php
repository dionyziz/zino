<?php

    global $libs;
    $libs->Load( 'event' );

    class NotificationFinder extends Finder {
        protected $mModel = 'Notification';

        public function FindByUser( $user, $offset = 0, $limit = 20 ) {
            $notif = New Notification();
            $notif->Fromuserid = $user->Id;

            return $this->FindByPrototype( $notif, $offset, $limit, array( 'Id', 'DESC' ) );
        }
        public function FindByCommentAndUser( $comment, $user ) {
            $query = $this->mDb->Prepare( "SELECT 
                        *
                    FROM
                        :notify RIGHT JOIN :events
                            ON notification_eventid = event_id
                    WHERE
                        `notification_touserid` = :userid AND
                        `event_typeid` = :typeid
                        `event_itemid` = :commentid
                    LIMIT 
                        1;" );
            
            $query->BindTable( 'notify' );
            $query->BindTable( 'events' );
            $query->Bind( 'userid', $user->Id );
            $query->Bind( 'typeid', EVENT_COMMENT_CREATED );
            $query->Bind( 'commentid', $comment->Id );
            
            $res = $query->Execute();
            if ( $res->Results() ) {
                return New Notification( $res->FetchArray() );
            }
            else {
                return false;
            }
        }
    }

    function Notification_FieldByEvent( $event ) {
        w_assert( $event->Typeid != 0 );

        if ( $event->Typeid == EVENT_COMMENT_CREATED ) {
            $comment = $event->Item;
            if ( $comment->Parentid == 0 ) {
                return 'replies';
            }
            switch ( Type_FromObject( $comment->Item ) ) {
                case TYPE_JOURNAL:
                    return 'journals';
                case TYPE_IMAGE:
                    return 'photos';
                case TYPE_POLL:
                    return 'polls';
                case TYPE_USERPROFILE:
                    return 'profile';
            }
        }
        else if ( $event->Typeid == EVENT_FRIENDRELATION_CREATED ) {
            return 'friends';
        }

        throw New Exception( 'Invalid event on Notification_FieldByEvent' );
    }

    class Notification extends Satori {
        protected $mDbTableAlias = 'notify';

        public function GetItem() {
            w_assert( $this->Event->Exists(), 'Event does not exist' );

            return $this->Event->Item;
        }
        public function GetFromUser() {
            return $this->Event->User;
        }
        public function Email() {
            global $rabbit_settings;
            
            $touser = New User( $this->Touserid );

            switch ( $this->Event->Typeid ) {
                case EVENT_COMMENT_CREATED:
                    $target = 'notification/email/comment';
                    break;
                case EVENT_FRIENDRELATION_CREATED:
                    $target = 'notification/email/friend';
            }

            ob_start();
            $subject = Element( $target, $this );
            $message = ob_get_clean();

            // send an email
            mail( $touser->Profile->Email, $subject, $message, 'From: ' . $rabbit_settings[ 'applicationname' ] . ' <noreply@' . $rabbit_settings[ 'hostname' ] . ">\r\nReply-to: noreply <noreply@" . $rabbit_settings[ 'hostname' ] . '>' );
        }
        public function OnBeforeCreate() {
            global $water;
            $field = Notification_FieldByEvent( $this->Event );

            $touser = New User( $this->Touserid );

            $attribute = 'Email' . $field;
            if ( $touser->Preferences->$attribute == 'yes' && !empty( $touser->Profile->Email ) && $touser->Emailverified ) {
                $this->Email();
            }
            
            $attribute = 'Notify' . $field;
            $water->Trace( "Notify attribute", $attribute );
            if ( $touser->Preferences->$attribute != 'yes' ) {
                $water->Trace( "No notification for user " . $touser->Name, $touser->Preferences->$attribute );
                if ( !is_object( $touser ) ) {
                    die( "touser not an object" );
                }
                if ( !is_object( $touser->Preferences ) ) {
                    die( "prefernces not an object" );
                }
                return false;
            }
            $water->Trace( "New notification for user " . $touser->Name, $touser->Preferences->$attribute );

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
		public function GetSince() {
			return dateDiff( $this->Event->Created, NowDate() );
		}
    }

?>
