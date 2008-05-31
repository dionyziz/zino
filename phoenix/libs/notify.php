<?php

    function Notify_Types() {
        // array( name, field )
        // field: settings_notify_profile -> profile
        return array(
            array( 'NOTIFY_COMMENT_PROFILE', 'profile' ),
            array( 'NOTIFY_COMMENT_PHOTOS', 'photos' ),
            array( 'NOTIFY_COMMENT_JOURNALS', 'journals' ),
            array( 'NOTIFY_COMMENT_REPLY', 'replies' ),
            array( 'NOTIFY_FRIEND_ADDED', 'friends' )
        );
    }

    $types = Notify_Types();
    foreach ( $types as $key => $type ) {
        define( $type[ 0 ], $key );
    }

    function Notify_FieldByType( $type ) {
        $types = Notify_Types();

        return $types[ $type ][ 1 ];
    }

    class NotifyFinder extends Finder {
        protected $mModel = 'Notify';

        public function FindByUser( $user, $offset = 0, $limit = 20 ) {
            $notify = New Notify();
            $notify->Touserid = $user->Id;

            return $this->FindByPrototype( $notify, $offset, $limit, array( 'Id', 'DESC' ) );
        }
    }

    class Notify extends Satori {
        protected $mDbTableAlias = 'notify';

        public function Email() {
            // send an email
        }
        public function OnCreate() {
            $attribute = 'Email' . Notify_FieldByType( $this->Typeid );
            if ( $this->ToUser->Settings->$attribute == 'yes' && !empty( $this->ToUser->Email ) && $this->ToUser->Emailverified ) {
                $this->Email();
            }
        }
        public function Relations() {
            $this->ToUser = $this->HasOne( 'User', 'Touserid' );
        }
        public function OnBeforeUpdate() {
            throw New Exception( 'Notifications cannot be edited!' );
        }
    }

?>
