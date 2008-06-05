<?php

    global $libs;
    $libs->Load( 'relation/type' );

    class FriendRelationFinder extends Finder {
        protected $mModel = 'FriendRelation';

        public function FindByUser( $user, $offset = 0, $limit = 10000 ) {
			// Remove this
			global $water;
			$water->Trace( 'FriendRelationFinder Trace Starts');

            $prototype = New FriendRelation();
            $prototype->Userid = $user->Id;
            
            return $trace = $this->FindByPrototype( $prototype, $offset, $limit );
			$water->Trace( 'FriendRelationFinder::FindByUser returns: ' , $trace );
			$water->Trace( 'FriendRelationFinder Trace Ends');
        }
        public function FindByFriend( $friend, $offset = 0, $limit = 10000 ) {
            $prototype = New FriendRelation();
            $prototype->Friendid = $friend->Id;

            return $this->FindByPrototype( $prototype, $offset, $limit );
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
        }
        protected function OnUpdate() {
            global $libs;
            $libs->Load( 'event' );

            $event = New Event();
            $event->Typeid = EVENT_FRIENDRELATION_UPDATED;
            $event->Itemid = $this->Id;
            $event->Userid = $this->Userid;
            $event->Save();
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
