<?php
    /*
        MySQL Database Driver for Rabbit (default)
        Developer: Dionyziz
    */
    
    class DatabaseDriver_MySQL implements DatabaseDriver {
        public function GetName() {
            return 'MySQL';
        }
        public function LastAffectedRows( $driver_link ) {
            return mysql_affected_rows( $driver_link );
        }
        public function LastInsertId( $driver_link ) {
            return mysql_insert_id( $driver_link );
        }
        public function Query( $sql, $driver_link ) {
            return mysql_query( $sql, $driver_link );
        }
        public function SelectDb( $name, $driver_link ) {
            return mysql_select_db( $name, $driver_link );
        }
        public function Connect( $host, $username, $password, $persist = true ) {
            if ( $persist ) {
                return mysql_pconnect( $host, $username, $password );
            }
            return mysql_connect( $host, $username, $password, $flags );
        }
        public function LastErrorNumber( $driver_link ) {
            return mysql_errno( $driver_link );
        }
        public function LastError( $driver_link ) {
            return mysql_error( $driver_link );
        }
        public function NumRows( $driver_resource ) {
            return mysql_num_rows( $driver_resource );
        }
        public function NumFields( $driver_resource ) {
            return mysql_num_fields( $driver_resource );
        }
        public function FetchAssociativeArray( $driver_resource ) {
            return mysql_fetch_assoc( $driver_resource );
        }
        public function FetchField( $driver_resource, $offset ) {
            return mysql_fetch_field( $driver_resource, $offset );
        }
    }
    
?>
