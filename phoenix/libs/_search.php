<?php

    class Search {
        public $mDb;

        public function Get( $prototype ) {
            $class = get_class( $prototype );

            $table = $prototype->GetTableAlias();

            $query = $this->mDb->Prepare( "SELECT * FROM :$table;" );
            $query->Bind( $table );

            $res = $query->Execute();
            $ret = array();
            while ( $row = $res->FetchArray() ) {
                $ret[] = New $class( $row );
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
        }
    }

?>
