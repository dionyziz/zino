<?php
    /*
        Microsoft SQL Database Driver for Rabbit
        Developer: Dionyziz
    */
    
    class DatabaseDriver_MSSQL_ResultSet {
        private $mRows;
        private $mNumRows;
        private $mMSSQLResource;
        
        public function NumFields() {
            return mssql_num_fields( $this->mMSSQLResource );
        }
        public function NumRows() {
            return $this->mNumRows;
        }
        public function FetchArray() {
            if ( empty( $this->mRows ) ) {
                return false;
            }
            return array_shift( $this->mRows );
        }
        public function FetchField( $offset ) {
            return mssql_fetch_field( $this->mMSSQLResource, $offset );
        }
        public function DatabaseDriver_MSSQL_ResultSet( DatabaseDriver_MSSQL $driver, $resource ) {
            $this->mRows = array();
            // we need to buffer all results returned in order to be able to server numrows requests
            while ( $row = mssql_fetch_assoc( $resource ) ) {
                $this->mRows[] = $row;
            }
            mssql_free_result( $resource );
            $this->mNumRows = count( $this->mRows );
            $this->mMSSQLResource = $resource;
        }
    }
    
    class DatabaseDriver_MSSQL implements DatabaseDriver {
        public function GetName() {
            return 'MSSQL';
        }
        public function LastAffectedRows( $driver_link ) {
            return mssql_rows_affected( $driver_link );
        }
        public function LastInsertId( $driver_link ) {
            $sql = 'SELECT SCOPE_IDENTITY() AS last_insert_id';
            
            $res = $this->Query( $sql, $driver_link );
            $row = $this->FetchAssociativeArray( $res );
            
            return $row[ 'last_insert_id' ];
        }
        public function Query( $sql, $driver_link ) {
            $res = mssql_query( $driver_link, $sql );
            
            if ( is_bool( $res ) ) {
                return $res;
            }

            return New DatabaseDriver_MSSQL_ResultSet( $this, $res );
        }
        public function SelectDb( $name, $driver_link ) {
            return mssql_select_db( $name, $driver_link );
        }
        public function Connect( $host, $username, $password, $persist = false ) {
            if ( $persist ) {
                return mssql_pconnect( $host, $username, $password );
            }
            return mssql_connect( $host, $username, $password );
        }
        public function LastErrorNumber( $driver_link ) {
            return 0;
        }
        public function LastError( $driver_link ) {
            return '';
        }
        public function NumRows( $driver_resource ) {
            return $driver_resource->NumRows();
        }
        public function NumFields( $driver_resource ) {
            return $driver_resource->NumFields();
        }
        public function FetchAssociativeArray( $driver_resource ) {
            return $driver_resource->FetchArray();
        }
        public function FetchField( $driver_resource, $offset ) {
            return $driver_resource->FetchField( $offset );
        }
    }
?>
