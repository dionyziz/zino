<?php
    /*
        MASKED
        By: Dionyziz
        Reason: Events optimizations
    */
    
    global $libs;
    
    $libs->Load( 'event' );

    class NotificationFinder extends Finder {
        protected $mModel = 'Notification';

        public function DeleteByEntity( $entity ) {
            $query = $this->mDb->Prepare( 
                'DELETE 
                FROM
                    :notify
                WHERE 
                    `notify_itemid` = :itemid AND 
                    `notify_typeid` IN :typeids;'
            );

            $query->BindTable( 'notify' );
            $query->Bind( 'itemid', $entity->Id );
            $query->Bind( 'typeids', Event_TypesByModel( strtoupper( get_class( $entity ) ) ) );

            return $query->Execute()->Impact();
        }
        public function FindByUserAfterId( $user, $id = 0, $offset = 0, $limit = 20 ) {
            if ( $user instanceof User ) {
                $userid = $user->Id;
            }
            else {
                w_assert( is_int( $user ) );
                $userid = $user;
            }
            w_assert( is_int( $id ) );

            $query = $this->mDb->Prepare(
                "SELECT
                    *
                FROM
                    :notify
                WHERE
                    `notify_touserid` = :userid
                    AND `notify_eventid` < :id
                ORDER BY
                    `notify_eventid` DESC
                LIMIT
                    :offset, :limit;" );
            $query->BindTable( 'notify' );
            $query->Bind( 'userid', $userid );
            $query->Bind( 'id', $id );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit + 6 );
        
            $res = $query->Execute();

            $ret = array();
            $i = 0;
            while ( $row = $res->FetchArray() ) {
                if ( $i < $limit ) {
                    $notif = New Notification( $row );
                    $ret[] = $notif;
                }
                ++$i;
            }

            return New Collection( $ret, $i );
        }
        public function FindByUser( User $user, $offset = 0, $limit = 20 ) {
            /*
            $prototype = New Notification();
            $prototype->Touserid = $user->Id;
            
            return $this->FindByPrototype( $prototype, $offset, $limit + 6 );
            */
            
            $query = $this->mDb->Prepare( 
                "SELECT
                    *
                FROM
                    :notify
                WHERE
                    `notify_touserid` = :userid
                ORDER BY
                    `notify_eventid` DESC
                LIMIT
                    :offset, :limit;" );

            $query->BindTable( 'notify' );
            $query->Bind( 'userid', $user->Id );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit + 6 );
        
            $res = $query->Execute();

            $ret = array();
            $i = 0;
            while ( $row = $res->FetchArray() ) {
                if ( $i < $limit ) {
                    $notif = New Notification( $row );
                    $ret[] = $notif;
                }
                ++$i;
            }

            return New Collection( $ret, $i );
        }
        public function DeleteByCommentAndUser( Comment $comment, User $user ) {
            $query = $this->mDb->Prepare(
                "DELETE
                FROM
                    :notify
                USING
                    :notify 
                WHERE
                    `notify_typeid` = :typeid AND
                    `notify_itemid` = :commentid AND
                    `notify_touserid` = :userid;"
            );

            $query->BindTable( 'notify' );
            $query->Bind( 'typeid', EVENT_COMMENT_CREATED );
            $query->Bind( 'commentid', $comment->Id );
            $query->Bind( 'userid', $user->Id );

            return $query->Execute()->Impact();
        }
        public function FindByComment( Comment $comment ) {
            global $water; 

            $query = $this->mDb->Prepare( 
                "SELECT 
                    *
                FROM
                    :notify
                WHERE
                    `notify_typeid` = :typeid AND
                    `notify_itemid` = :commentid
                LIMIT 1;"
            );
        
            $query->BindTable( 'notify' );
            $query->Bind( 'typeid', EVENT_COMMENT_CREATED );
            $query->Bind( 'commentid', $comment->Id );
            
            $res = $query->Execute();
            if ( $res->Results() ) {
                $row = $res->FetchArray();
                $notif = New Notification( $row );

                return $notif;
            }
            else {
                $water->Warning( "No results for comment " . $comment->Id );
            }

            return false;
        }
        public function FindByRelation( FriendRelation $relation ) {
            global $water; 

            $query = $this->mDb->Prepare(
                "SELECT
                    *
                FROM
                    :notify
                WHERE
                    `notify_typeid` = :typeid AND
                    `notify_itemid` = :relationid
                LIMIT
                    1;"
            );

            $query->BindTable( 'notify' );
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
        
            $query = $this->mDb->Prepare(
                "SELECT
                    *
                FROM
                    :notify
                WHERE
                    `notify_typeid` = :typeid AND
                    `notify_itemid` = :tagid
                LIMIT
                    1;"
            );
             
            $query->BindTable( 'notify' );
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

    function Notification_GetField( $notification ) {
        w_assert( $notification->Typeid != 0 );

        switch ( $notification->Typeid ) {
            case EVENT_COMMENT_CREATED:
                $comment = $notification->Item;
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
                break;
            case EVENT_FRIENDRELATION_CREATED:
                return 'friendaddition';
            case EVENT_IMAGETAG_CREATED:
                return 'phototag';
            case EVENT_FAVOURITE_CREATED:
                return 'favourite';
        }
        
        return false;
    }

    class Notification extends Satori {
        protected $mDbTableAlias = 'notify';

        public function CopyUserFrom( $value ) {
            $this->mRelations[ 'User' ]->CopyFrom( $value );
        }
        public function CopyItemFrom( $value ) {
            $this->mRelations[ 'Item' ]->CopyFrom( $value );
        }
        public function __get( $key ) {
            switch ( $key ) {
                case 'Item':
                    return $this->Item;
                case 'Id':
                    return $this->Eventid;
                case 'Userid': // from user id
                    return $this->Fromuserid;
                default:
                    return parent::__get( $key );
            }
        }
        public function Email() {
            global $rabbit_settings;
            global $libs;

            $libs->Load( 'rabbit/helpers/email' );
            
            switch ( $this->Typeid ) {
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
            
            die( 'Typeof item ' . get_class( $this->Item ) );
            
            switch ( $this->Typeid ) {
                case EVENT_COMMENT_CREATED:
                    $comment = $this->Item;
                    $entity = $comment->Item;

                    if ( $comment->Parentid > 0 ) {
                        $this->Touserid = $comment->Parent->Userid;
                    }
                    else {
                        switch ( get_class( $entity ) ) {
                            case 'User':
                                $this->Touserid = $entity->Id;
                                break;
                            case 'Image':
                            case 'Journal':
                            case 'Poll':
                                $this->Touserid = $entity->Userid;
                                break;
                        }
                    }
                    break;
                case EVENT_FRIENDRELATION_CREATED:
                    $this->Touserid = $this->Item->Friendid;
                    break;
	        	case EVENT_IMAGETAG_CREATED:
                    $this->Touserid = $this->Item->Personid;
                    break;
                case EVENT_FAVOURITE_CREATED:
                    $this->Touserid = $this->Item->Item->Userid;
                    break;
                case EVENT_USER_BIRTHDAY:
                    $this->Touserid = $this->Itemid;
                    break;
            }
            
            if ( $this->Touserid == $this->Fromuserid ) {
                die( 'Same origin' );
                return false;
            }

            $this->mRelations[ 'ToUser' ]->Rebuild();
            $field = Notification_GetField( $this );

            if ( $field === false ) {
                die( 'No field' );
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
                die( 'No notifications' );
                return false;
            }
            return true;
        }
        protected function OnCreate() {
            global $libs;
            global $user;
            
            $libs->Load( 'image/tag' );
            $libs->Load( 'rabbit/event' );
            
            FireEvent( 'NotificationCreated', $this );
        }
        protected function Relations() {
            global $libs;

            $libs->Load( 'comment' );
            $libs->Load( 'image/tag' );
            $libs->Load( 'relation/relation' );
            $libs->Load( 'favourite' );
            
            $this->User = $this->HasOne( 'User', 'Userid' );
            if ( $this->Typeid ) {
                $model = Event_ModelByType( $this->Typeid );
                $this->Item = $this->HasOne( $model, 'Itemid' );
            }
            
            $this->ToUser = $this->HasOne( 'User', 'Touserid' );
            $this->FromUser = $this->HasOne( 'User', 'Fromuserid' );
        }
        protected function OnBeforeUpdate() {
            throw New Exception( 'Notifications cannot be edited!' );
        }
        protected function LoadDefaults() {
            $this->Created = NowDate();
        }
    }
?>
