<?php

    /*
    class UserSearch extends Search {
        protected $mModel = 'User';

        public function GetLatest( $n = 5 ) {
            $this->Limit = $n;
            $this->OrderBy = 'Created';

            return $this->Get();
        }
    }
    */

    class Search extends Overloadable {
        protected $mDb;
        protected $mDbTable;
        protected $mLimit;
        protected $mOffset;
        protected $mSortBy;
        protected $mOrder;

        protected function SetLimit( $limit ) {
            $this->mLimit = $limit;
        }
        protected function SetOffset( $offset ) {
            $this->mOffset = $offset;
        }
        protected function SetSortBy( $table = false, $field ) {
            if ( $table == false ) {
                $table == $this->mDbTable;
            }

            $this->mSortBy = strtolower( "`$table`.`$field`" );
            $this->mOrder = 'DESC';
        }
        protected function SetOrder( $order ) {
            $this->mOrder = strtoupper( $order );
        }
        private function CreateQuery() {
            $query = 'SELECT * FROM ' . $this->mDbTable . ' ';
            
            if ( $this->mSortBy != NULL ) {
                $query .= ' ORDER BY ' . $this->mSortBy . ' ' . $this->mOrder . ' ';
            }
            $query .= ' LIMIT ' . $this->mOffset . ', ' . $this->mLimit . ';';

            return $this->mDb->Prepare( $query );
        }
        public function Get() {
            $res = $this->CreateQuery()->Execute();
            $ret = array();
            while ( $row = $res->FetchArray() ) {
                $ret[] = New $this->mModel( $row );
            }

            return $ret;
        }
        public function __construct( $database = false ) {
            global $db;

            if ( $database !== false ) {
                $this->mDb = $database;
            }
            else {
                $this->mDb = $db;
            }

            $prototype = New $this->mModel(); // MAGIC!
            $this->mDbTable = $prototype->DbTable;
            $this->mLimit = 20;
            $this->mOffset = 0;
        }
    }

?>
