<?php
    /*
        MySQL Database Driver for Rabbit (default)
        Developer: Dionyziz
    */
    
    class DatabaseDriver_MySQL implements DatabaseDriver {
        protected function GetName() {
            return 'MySQL';
        }
        protected function LastAffectedRows( $driver_link ) {
            return mysql_affected_rows( $driver_link );
        }
        protected function LastInsertId( $driver_link ) {
            return mysql_insert_id( $driver_link );
        }
        protected function Query( $sql, $driver_link ) {
            return mysql_query( $sql, $driver_link );
        }
        protected function SelectDb( $name, $driver_link ) {
            return mysql_select_db( $name, $driver_link );
        }
        protected function Connect( $hostname, $username, $password, $flags ) {
            return mysql_connect( $hostname, $username, $password, $flags );
        }
        protected function LastErrorNumber( $driver_link ) {
            return mysql_errno( $driver_link );
        }
        protected function LastError( $driver_link ) {
            return mysql_error( $driver_link );
        }
        protected function NumRows( $driver_resource ) {
            return mysql_num_rows( $driver_resource );
        }
        protected function NumFields( $driver_resource ) {
            return mysql_num_fields( $driver_resource );
        }
        protected function FetchAssociativeArray( $driver_resource ) {
            return mysql_fetch_assoc( $driver_resource );
        }
        protected function FetchField( $driver_resource, $offset ) {
            return mysql_fetch_field( $driver_resource, $offset );
        }
    }
    
?>
