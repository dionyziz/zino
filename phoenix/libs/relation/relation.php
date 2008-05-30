<?php

    global $libs;
    $libs->Load( 'relation/type' );

    class RelationFinder extends Finder {
        protected $mModel = 'Relation';

        public function FindByUser( $user, $offset = 0, $limit = 10000 ) {
            $prototype = New Relation();
            $prototype->Userid = $user->Id;
            
            return $this->FindByPrototype( $prototype, $offset, $limit );
        }
        public function FindByFriend( $friend, $offset = 0, $limit = 10000 ) {
            $prototype = New Relation();
            $prototype->Friendid = $user->Id;

            return $this->FindByPrototype( $prototype, $offset, $limit );
        }
    }

    class Relation extends Satori {
        protected $mDbTableAlias = 'relations';

        public function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Friend = $this->HasOne( 'User', 'Friendid' );
        }
        public function LoadDefaults() {
            global $user;

            $this->Userid = $user->Id;
            $this->Created = NowDate();
        }
    }

?>
