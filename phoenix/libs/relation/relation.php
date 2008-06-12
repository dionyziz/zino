<?php
    global $libs;

    define( 'FRIENDS_NONE', 0 );
    define( 'FRIENDS_A_HAS_B', 1 );
    define( 'FRIENDS_B_HAS_A', 2 );
    define( 'FRIENDS_BOTH', FRIENDS_A_HAS_B | FRIENDS_B_HAS_A );

    $libs->Load( 'relation/type' );

    class FriendRelationFinder extends Finder {
        protected $mModel = 'FriendRelation';

        public function FindByUser( $user, $offset = 0, $limit = 10000 ) {
            $prototype = New FriendRelation();
            $prototype->Userid = $user->Id;
            
            return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Id', 'DESC' ) );
        }
        public function FindByFriend( $friend, $offset = 0, $limit = 10000 ) {
            $prototype = New FriendRelation();
            $prototype->Friendid = $friend->Id;

            return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Id', 'DESC' ) );
        }
        public function IsFriend( User $a, User $b ) {
            $status = FRIENDS_NONE;

            $prototype = New FriendRelation();
            $prototype->Userid = $a->Id;
            $prototype->Friendid = $b->Id;
            if ( $this->FindByPrototype( $prototype ) !== false ) {
                $status |= FRIENDS_A_HAS_B;
            }
            
            $prototype = New FriendRelation();
            $prototype->Userid = $b->Id;
            $prototype->Friendid = $a->Id;
            if ( $this->FindByPrototype( $prototype ) !== false ) {
                $status |= FRIENDS_B_HAS_A;
            }

            return $status;
        }
    }

    class FriendRelation extends Satori {
        protected $mDbTableAlias = 'relations';
       
        protected function OnCreate() {
            global $libs;

            $libs->Load( 'event' );

            $event = New Event();
            $event->Typeid = EVENT_FRIENDRELATION_CREATED;
            $event->Itemid = $this->Id;
            $event->Userid = $this->Userid;
            $event->Save();

            ++$this->User->Count->Relations;
            $this->User->Count->Save();
        }
        /*
        protected function OnUpdate() {
            global $libs;
            
            $libs->Load( 'event' );

            $event = New Event();
            $event->Typeid = EVENT_FRIENDRELATION_UPDATED;
            $event->Itemid = $this->Id;
            $event->Userid = $this->Userid;
            $event->Save();
        }
        */
        protected function OnDelete() {
            global $libs;
            $libs->Load( 'notify' );
            
            --$this->User->Count->Relations;
            $this->User->Count->Save();

            $finder = New NotificationFinder();
            $notif = $finder->FindByRelation( $this );

            if ( !is_object( $notif ) ) {
                return;
            }
            
            $notif->Delete();
        }
        public function GetType() {
            return $this->RelationType->Text;
        }
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Friend = $this->HasOne( 'User', 'Friendid' );
            $this->RelationType = $this->HasOne( 'RelationType', 'Typeid' );
        }
        public function LoadDefaults() {
            global $user;

            $this->Userid = $user->Id;
            $this->Created = NowDate();
        }
    }

?>
