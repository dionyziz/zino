<?php
    // implement this interface to add support for a different database
    interface DatabaseDriver {
        // returns number of affected rows by the last query performed
        public function LastAffectedRows( $link );
        // returns the insertid of the last query performed
        public function LastInsertId( $link );
        // executes an SQL query
        public function Query( $sql, $link );
        // selects a database for performing queries on
        public function SelectDb( $name, $link );
        // connects to the database and authenticates
        public function Connect( $host, $username, $password, $persist = true );
        // retrieves the last error number
        public function LastErrorNumber( $link );
        // retrieves the last error message as a user-friendly string
        public function LastError( $link );
        // retrieves the number of rows in the resultset identified by passed resource
        public function NumRows( $driver_resource );
        // retrieves the number of fields in the resultset identified by passed resource
        public function NumFields( $driver_resource );
        // fetches the next row of the resultset in an associative array, or returns boolean false 
        // if there are no more rows
        public function FetchAssociativeArray( $driver_resource );
        // fetches information about field #offset in the resultset in the form of an object
        public function FetchField( $driver_resource, $offset );
        // retrieves a user-friendly name for this driver as a string
        public function GetName();
		// get a native database data type describing string by constant
		public function DataTypeByConstant( $constant );
		// get a rabbit DB data type from a native datatype describing string 
		public function ConstantByDataType( $datatype );
        // get an associative array pointing from data type constants to native database driver 
        // string representations of the types to-be-used within queries
        public function DataTypes();
        // construct a DBField based on info returned by the database
        public function ConstructField( DBField $target, $info );
    }
?>
