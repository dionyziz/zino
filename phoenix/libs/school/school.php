<?php

    global $libs;

    $libs->Load( 'school/institution' );

    $types = array(
        'SCHOOL_ELEMENTARY',
        'SCHOOL_JUNIORHIGH',
        'SCHOOL_HIGH',
        'SCHOOL_TEE',
        'SCHOOL_TEI',
        'SCHOOL_AEI'
    );

    foreach ( $types as $number => $type ) {
        define( $type, $number + 1 );
    }

    class SchoolException extends Exception {
    }

    class SchoolFinder extends Finder {
        protected $mModel = 'School';

        public function Find( $placeid = false, $typeid = false, $offset = 0, $limit = 10000 ) {
            $prototype = New School();
            if ( $placeid !== false ) {
                $prototype->Placeid = $placeid;
            }
            if ( $typeid !== false ) {
                $prototype->Typeid = $typeid;
            }
            $prototype->Approved = 1;
            return $this->FindByPrototype( $prototype, $offset, $limit, 'Name' );
        }
        public function FindNotApproved( $offset = 0, $limit = 10000 ) {
            $prototype = new School();            
            $prototype->Approved = 0;            
            return $this->FindByPrototype( $prototype, $offset, $limit, 'Name' );
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

        public function __set( $key, $value ) {
            if ( $key == 'Typeid' ) {
                if ( $value < 1 || $value > 6 ) {
                    throw New SchoolException( 'Type id must be between 1 and 6, inclusive' );
                }
            }
            parent::__set( $key, $value );
        }
        public function __get( $key ) {
            switch ( $key ) {
                case 'Numstudents':
                    $query = $this->mDb->Prepare(
                        'SELECT
                            COUNT(*) AS numusers
                        FROM
                            :userprofiles
                        WHERE
                            `profile_schoolid`=:schoolid;'
                    ); // TODO: Optimize (denormalize)
                    $query->BindTable( 'userprofiles' );
                    $query->Bind( 'schoolid', $this->Id );
                    return array_shift( $query->Execute()->FetchArray() );
                default:
                    return parent::__get( $key );
            }
        }
        public function OnCommentCreate() {
            ++$this->Numcomments;
            $this->Save();
        }
        public function OnCommentDelete() {
            --$this->Numcomments;
            $this->Save();
        }
        protected function Relations() {
            $this->Place = $this->HasOne( 'Place', 'Placeid' );
            $this->Students = $this->HasMany( 'UserFinder', 'FindBySchool', $this );
            $this->Institution = $this->HasOne( 'Institution', 'Institutionid' );
            $this->Album = $this->HasOne( 'Album', 'Albumid' );
        }
        protected function LoadDefaults() {
            $this->Created = NowDate();
            $this->Approved = 0;
        }
    }

?>
