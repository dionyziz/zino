<?php

    global $libs;
    $libs->Load( 'relation/type' );

    class FriendRelationFinder extends Finder {
        protected $mModel = 'FriendRelation';

        public function FindByUser( $user, $offset = 0, $limit = 10000 ) {
            $prototype = New FriendRelation();
            $prototype->Userid = $user->Id;
            
            return $this->FindByPrototype( $prototype, $offset, $limit );
        }
        public function FindByFriend( $friend, $offset = 0, $limit = 10000 ) {
            $prototype = New FriendRelation();
            $prototype->Friendid = $user->Id;

            return $this->FindByPrototype( $prototype, $offset, $limit );
        }
    }

    class FriendRelation extends Satori {
        protected $mDbTableAlias = 'relations';
        
        public function GetType() {
            return $this->RelationType->Name;
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
