<?php
    global $libs;
    
    $libs->Load( 'event' );
    $libs->Load( 'notify/finder' );

    function Notification_GetField( $notification ) {
        w_assert( $notification->Typeid != 0 );

        switch ( $notification->Typeid ) {
            case EVENT_COMMENT_CREATED:
                $comment = $notification->Item;
                if ( $comment->Parentid != 0 ) {
                    return 'reply';
                }
                switch ( Type_FromObject( $comment->Item ) ) {
                    case TYPE_JOURNAL:
                        return 'journalcomment';
                    case TYPE_PHOTO:
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
            case EVENT_USER_BIRTHDAY:
                return 'birthday';
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
            $fromaddress = 'noreply@' . $rabbit_settings[ 'hostname' ];
            switch ( $this->Typeid ) {
                case EVENT_COMMENT_CREATED:
                    $target = 'notification/email/comment';
					$fromaddress = 'teras' . $this->Item->Id . '-' .  substr( md5( 'beast' . $this->Item->Created . $this->Item->Id ), 0, 10 ) . '@' . $rabbit_settings[ 'hostname' ];
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
            Email( $this->ToUser->Name, $this->ToUser->Profile->Email, $subject, $message, $rabbit_settings[ 'applicationname' ], $fromaddress );
        }
        public function OnBeforeCreate() {
            global $water;
            global $libs;
            
            $libs->Load( 'user/settings' );
            $libs->Load( 'user/profile' );
            
            $this->DefineRelations();
            
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
                    $libs->Load( 'journal/journal' );
                    $libs->Load( 'poll/poll' );
                    
                    $this->Touserid = $this->Item->Item->Userid;
                    break;
                case EVENT_USER_BIRTHDAY:
                    // $this->Touserid predefined
                    break;
            }
            
            if ( $this->Touserid == $this->Fromuserid ) {
                return false;
            }

            $this->mRelations[ 'ToUser' ]->Rebuild();
            $field = Notification_GetField( $this );

            if ( $field === false ) {
                return;
            }
            
            $attribute = 'Email' . $field;
            if ( $this->ToUser->Preferences->$attribute == 'yes' && !empty( $this->ToUser->Profile->Email ) && $this->ToUser->Emailverified ) {
                $this->Email();
            }
            
            $attribute = 'Notify' . $field;
            if ( $this->ToUser->Preferences->$attribute != 'yes' ) {
                if ( !is_object( $this->ToUser ) ) {
                    die( "this->ToUser not an object" );
                }
                if ( !is_object( $this->ToUser->Preferences ) ) {
                    die( "prefernces not an object" );
                }
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
            
            $this->User = $this->HasOne( 'User', 'Fromuserid' );
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
