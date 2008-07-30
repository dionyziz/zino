<?php

    class SchoolException extends Exception {
    }

    class SchoolFinder extends Finder {
        protected $mModel = 'School';

        public function Find( $placeid = false, $typeid = false, $userid = false, $schoolid = false, $offset = 0, $limit = 10000 ) {
            $prototype = New School();
            if ( $placeid !== false ) {
                $prototype->Placeid = $placeid;
            }
            if ( $typeid !== false ) {
                $prototype->Typeid = $typeid;
            }
            if ( $userid !== false ) {
                $prototype->Userid = $userid;
            }
            if ( $schoolid !== false ) {
                $prototype->Id = $schoolid;
            }
            return FindByPrototype( $prototype, $offset, $limit, 'Name' );
        }

        public function Count() {
            $query = $this->mDb->Prepare( 'SELECT COUNT(*) AS schoolsnum FROM :schools;' );
            $query->BindTable( 'schools' );
            $res = $query->Execute();
            $row = $res->FetchArray();
            return ( int )$row[ 'schoolsnum' ];
        }
    }

    class School extends Satori {
        protected $mDbTableAlias = 'schools';

        protected function Relations() {
            $this->Place = $this->HasOne( 'Place', 'Placeid' );
            $this->Users = $this->HasMany( 'UserFinder', 'FindBySchool', $this );
        }

        protected function LoadDefaults() {
            $this->Created = NowDate();
            $this->Approved = 0;
        }
    }

?>
