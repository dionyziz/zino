<?php

    function Notification_Types() {
        // array( name, field )
        // field: settings_notify_profile -> profile
        return array(
            1 => array( 'NOTIFY_COMMENT_PROFILE', 'profile' ),
            2 => array( 'NOTIFY_COMMENT_IMAGE', 'photos' ),
            3 => array( 'NOTIFY_COMMENT_JOURNAL', 'journals' ),
            4 => array( 'NOTIFY_COMMENT_REPLY', 'replies' ),
            5 => array( 'NOTIFY_FRIEND_ADDED', 'friends' )
        );
    }

    $types = Notification_Types();
    foreach ( $types as $key => $type ) {
        define( $type[ 0 ], $key );
    }

    function Notification_FieldByType( $type ) {
        $types = Notification_Types();

        return $types[ $type ][ 1 ];
    }

    function Notification_TypeFromComment( $comment ) {
        switch ( $comment->Typeid ) {
            case TYPE_JOURNAL:
                return NOTIFY_COMMENT_JOURNAL;
            case TYPE_USERPROFILE:
                return NOTIFY_COMMENT_PROFILE;
            case TYPE_IMAGE:
                return NOTIFY_COMMENT_IMAGE;
            default:
                throw new Exception( 'Unkown type on Notification_TypeFromComment' );
        }
    }

    class NotificationFinder extends Finder {
        protected $mModel = 'Notification';

        public function FindByUser( $user, $offset = 0, $limit = 20 ) {
            $notif = New Notification();
            $notif->Touserid = $user->Id;

            return $this->FindByPrototype( $notif, $offset, $limit, array( 'Id', 'DESC' ) );
        }
    }

    class Notification extends Satori {
        protected $mDbTableAlias = 'notify';

        public function Email() {
            global $rabbit_settings;

            switch ( $this->Typeid ) {
                case NOTIFY_COMMENT_PROFILE:
                case NOTIFY_COMMENT_IMAGE:
                case NOTIFY_COMMENT_JOURNAL:
                case NOTIFY_COMMENT_REPLY:
                    $target = 'notification/email/comment';
                    break;
                case NOTIFY_FRIEND_ADDED:
                    $target = 'notification/email/friend';
            }

            ob_start();
            $subject = Element( $target, $this );
            $message = ob_get_clean();

            // send an email
            mail( $this->ToUser->Email, $subject, $message, 'From: ' . $rabbit_settings[ 'applicationname' ] . ' <noreply@' . $rabbit_settings[ 'hostname' ] . ">\r\nReply-to: noreply <noreply@" . $rabbit_settings[ 'hostname' ] . '>' );
        }
        public function OnCreate() {
            $attribute = 'Email' . Notification_FieldByType( $this->Typeid );
            if ( $this->ToUser->Preferences->$attribute == 'yes' && !empty( $this->ToUser->Email ) && $this->ToUser->Emailverified ) {
                $this->Email();
            }
        }
        public function Relations() {
            if ( $this->Exists() ) {
                switch ( $this->Typeid ) {
                    case NOTIFY_COMMENT_PROFILE:
                        $class = 'User';
                        break;
                    case NOTIFY_COMMENT_IMAGE:
                        $class = 'Image';
                        break;
                    case NOTIFY_COMMENT_JOURNAL:
                        $class = 'Journal';
                        break;
                    case NOTIFY_COMMENT_REPLY:
                        $class = 'Comment';
                        break;
                    case NOTIFY_FRIEND_ADDED:
                        break;
                    default:
                        throw New Exception( 'Unkown typeid on notification' );
                }
                $this->Item = $this->HasOne( $class, 'Itemid' );
            }

            $this->FromUser = $this->HasOne( 'User', 'Fromuserid' );
            $this->ToUser = $this->HasOne( 'User', 'Touserid' );
        }
        public function OnBeforeUpdate() {
            throw New Exception( 'Notifications cannot be edited!' );
        }
    }

?>
