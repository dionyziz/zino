<?php

    class SchoolFinder extends Finder {
        protected $mModel = 'School';

        public function Find( $placeid = NULL, $typeid = NULL, $offset = 0, $limit = 10000 ) {
            $prototype = New School();
            if ( isset( $placeid ) ) {
                $prototype->Placeid = $placeid;
            }
            if ( isset( $typeid ) ) {
                $prototype->Typeid = $typeid;
            }
            return FindByPrototype( $prototype, $offset, $limit, 'Name' );
        }

        public function FindUsers( $schoolid, $offset = 0, $limit = 10000 ) {
            $prototype = New User();
            $prototype->Schoolid = $schoolid;
            return FindByPrototype( $prototype, $offset, $limit, 'Name' );
        }

        public function FindByUser( $userid ) {
            $user = New User( $userid );
            $schoolid = $user->Schoolid;
            return New School( $schoolid );
        }
    }

    class School extends Satori {
        protected $mDbTableAlias = 'schools';

        protected function Relations() {
            $this->Place = $this->HasOne( 'Place', 'Placeid' );
            $this->Type = $this->HasOne( 'Type', 'Typeid' );
            $this->Users = $this->HasMany( 'SchoolFinder', 'FindUsers', $this );
        }

        public function OnBeforeDelete() {
            $this->Delid = 1;
            $this->Save();
            return false;
        }

        protected function LoadDefaults() {
            $this->Created = NowDate();
        }

    }

?>
