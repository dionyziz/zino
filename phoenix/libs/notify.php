<?php

    global $libs;
    $libs->Load( 'event' );

    class NotificationFinder extends Finder {
        protected $mModel = 'Notification';

        public function FindByUser( $user, $offset = 0, $limit = 20 ) {
            $notif = New Notification();
            $notif->Userid = $user->Id;

            return $this->FindByPrototype( $notif, $offset, $limit, array( 'Id', 'DESC' ) );
        }
    }

    class Notification extends Satori {
        protected $mDbTableAlias = 'notify';

        public function GetItem() {
            return $this->Event->Item;
        }
        public function GetFromUser() {
            return $this->Event->User;
        }
        public function Email() {
            global $rabbit_settings;

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
            mail( $this->ToUser->Email, $subject, $message, 'From: ' . $rabbit_settings[ 'applicationname' ] . ' <noreply@' . $rabbit_settings[ 'hostname' ] . ">\r\nReply-to: noreply <noreply@" . $rabbit_settings[ 'hostname' ] . '>' );
        }
        public function OnCreate() {
            $attribute = 'Email' . Notification_FieldByType( $this->Typeid );
            if ( $this->ToUser->Preferences->$attribute == 'yes' && !empty( $this->ToUser->Email ) && $this->ToUser->Emailverified ) {
                $this->Email();
            }
        }
        public function Relations() {
            $this->ToUser = $this->HasOne( 'User', 'Userid' );
            $this->Event = $this->HasOne( 'Event', 'Eventid' );
        }
        public function OnBeforeUpdate() {
            throw New Exception( 'Notifications cannot be edited!' );
        }
    }

?>
