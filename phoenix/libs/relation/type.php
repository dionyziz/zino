<?php

    class RelationTypeFinder extends Finder {
        protected $mModel = 'RelationType';

        public function FindAll( $offset = 0, $limit = 10000 ) {
            $prototype = New RelationType();
            
            return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Text', 'DESC' ) );
        }
    }

    class RelationType extends Satori {
        protected $mDbTableAlias = 'relationtypes';

        public function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
        }
        public function Save( $theuser = false ) {
            global $user;

            if ( !is_object( $theuser ) ) {
                $theuser = $user;
            }
            if ( !$theuser->HasPermission( PERMISSION_RELATIONTYPE_CREATE ) ) {
                throw New Exception( "Not enough permissions to create relaitontype!" );
            }

            parent::Save();
        }
        public function LoadDefaults() {
            global $user;

            $this->Userid = $user->Id;
            $this->Created = NowDate();
        }
    }

?>
