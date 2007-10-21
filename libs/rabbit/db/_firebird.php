<?php
    /*
        FireBird Database Driver for Rabbit
        Developer: Dionyziz
    */
    
    class DatabaseDriver_FireBird_ResultSet {
        // TODO
    }
    
    class DatabaseDriver_FireBird implements DatabaseDriver {
        private $mLastAffectedRows;
        
        public function GetName() {
            return 'FireBird/InterBase';
        }
        public function LastAffectedRows( $driver_link ) {
            return ibase_affected_rows( $driver_link );
        }
        public function LastInsertId( $driver_link ) {
            global $water;
            
            // This remains database-specific unfortunately, as mysql doesn't use generators
            $water->Warning( 'FireBird doesn\'t natively support grabbing the insertid; consider using a generator instead' );
            
            return 0;
        }
        public function Query( $sql, $driver_link ) {
            $ret = ibase_query( $driver_link, $sql );
            
            if ( $ret === false ) {
                return false;
            }
            if ( preg_match( '#^\s*(INSERT|UPDATE|DELETE)#', $sql ) ) {
                $this->mLastAffectedRows = $ret;
                return true;
            }
            
            return $ret;
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
            return mysql_num_rows( $driver_resource );
        }
        public function NumFields( $driver_resource ) {
            return ibase_num_fields( $driver_resource );
        }
        public function FetchAssociativeArray( $driver_resource ) {
            return ibase_fetch_assoc( $driver_resource );
        }
        public function FetchField( $driver_resource, $offset ) {
            return ibase_field_info( $driver_resource, $offset );
        }
    }
?>
