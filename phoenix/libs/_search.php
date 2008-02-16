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

    class Search {
        protected $mDb;
        protected $mDbTable;

        public function Get() {
            $query = $this->mDb->Prepare( 'SELECT * FROM ' . $this->mDbTable . ';' );

            $res = $query->Execute();
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
        }
    }

?>
