<?php
    /*
        FireBird Database Driver for Rabbit
        Developer: Dionyziz
    */
    
    class DBDriver_FireBird_ResultSet {
        private $mRows;
        private $mNumRows;
        private $mFirebirdResource;
        
        public function NumFields() {
            return ibase_num_fields( $this->mFirebirdResource );
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
            return ibase_field_info( $this->mFirebirdResource, $offset );
        }
        public function DatabaseDriver_FireBird_ResultSet( DatabaseDriver_FireBird $driver, $resource ) {
            $this->mRows = array();
            // we need to buffer all results returned in order to be able to server numrows requests
            while ( $row = ibase_fetch_assoc( $resource ) ) {
                $this->mRows[] = $row;
            }
            $this->mNumRows = count( $this->mRows );
            $this->mFirebirdResource = $resource;
        }
    }
    
    class DatabaseDriver_FireBird implements DBDriver {
        private $mLastAffectedRows;
        
        public function GetName() {
            return 'FireBird/InterBase';
        }
        public function LastAffectedRows( $driver_link ) {
            return ibase_affected_rows( $driver_link );
        }
        public function LastInsertId( $driver_link ) {
            global $water;
            
            // This remains database-specific unfortunately, as firebird uses generators and mysql doesn't
            $water->Warning( 'FireBird doesn\'t natively support grabbing the insertid; consider using a generator instead' );
            
            return 0;
        }
        public function Query( $sql, $driver_link ) {
            $res = ibase_query( $driver_link, $sql );
            
            if ( $res === false ) {
                return false;
            }
            if ( preg_match( '#^\s*(INSERT|UPDATE|DELETE)#', $sql ) ) {
                $this->mLastAffectedRows = $res;
                return true;
            }
            
            return New DatabaseDriver_FireBird_ResultSet( $this, $res );
        }
        public function SelectDb( $name, $driver_link ) {
            global $water;
            
            $water->Warning( 'Firebird database names are integral to their "database" connection string; please remove the separate database name parameter and modify your host string to contain your dgb file instead' );
        }
        public function Connect( $host, $username, $password, $persist = false ) {
            if ( $persist ) {
                return ibase_pconnect( $host, $username, $password );
            }
            return ibase_connect( $host, $username, $password );
        }
        public function LastErrorNumber( $driver_link ) {
            return ibase_errcode();
        }
        public function LastError( $driver_link ) {
            return ibase_errmsg();
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
