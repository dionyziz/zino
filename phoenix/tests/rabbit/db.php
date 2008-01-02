<?php
    class TestRabbitDb extends Testcase {
        private $mFirstDatabase;
        
        public function TestClassesExist() {
            $this->Assert( class_exists( 'Database' ), 'Class Database doesn\'t exist' );
        }
        public function TestSettings() {
            global $rabbit_settings;
            
            $this->Assert( isset( $rabbit_settings[ 'databases' ] ), '"databases" setting not specified -- cannot continue testing without some databases to work on' );
            $this->Assert( is_array( $rabbit_settings[ 'databases' ] ), '"databases" setting is not an array -- cannot continue testing without some databases to work on' );
            $this->Assert( count( $rabbit_settings[ 'databases' ] ), '"databases" setting is empty -- cannot continue testing without some databases to work on' );
        }
        public function TestMethodsExist() {
            $this->mFirstDatabase = $GLOBALS[ reset( array_keys( $rabbit_settings[ 'databases' ] ) ) ];
            $this->Assert( $this->mFirstDatabase instanceof Database, 'Your first defined database does not appear to be consistent' );
            $this->Assert( method_exists( $this->mFirstDatabase, 'Tables' ), 'Method Database->Tables() does\'t exist' );
        }
        public function TestPublicImport() {
            global $rabbit_settings;
            
            foreach ( $rabbit_settings[ 'databases' ] as $key => $database ) {
                $this->Assert( is_string( $key ), 'Each database alias should be a string' );
                $this->Assert( isset( $database[ 'name' ] ), '"name" attribute is obligatory for all databases' );
                $this->Assert( isset( $database[ 'driver' ] ), '"driver" attribute is obligatory for all databases' );
                $this->Assert( isset( $database[ 'hostname' ] ), '"hostname" attribute is obligatory for all databases' );
                $this->Assert( isset( $GLOBALS[ $key ] ), 'Database was not imported into the global namespace' );
                $this->Assert( is_object( $GLOBALS[ $key ] ), 'Database imported into the global namespace was not an object' );
                $this->Assert( $GLOBALS[ $key ] instanceof Database, 'Database imported into the global namespace does not appear to be a Database instance' );
            }
        }
        
        /*
        public function TestTableList() {
            $tables = $this->mFirstDatabase->Tables();
            $this->Assert( is_array( $tables ), 'Value returned by Database->Tables() must be an array' );
            $this->Assert( count( $tables ), 'Your first database does not contain any tables -- cannot continue testing without some tables to work on' );
            foreach ( $tables as $table ) {
                $this->Assert( is_object( $table ), 'Item of array returned by Database->Tables() was not an object' );
                $this->Assert( $table instanceof DBTable, 'Item of array returned by Database->Tables() was not an instance of DBTable' );
                $fields = $table->Fields;
                $this->Assert( is_array( $fields ), 'Value of attribute DBTable->Fields must be an array' );
                $this->Assert( count( $fields ), 'One of your database tables does not contain any columns -- cannot continue testing without some fields to work on' );
                foreach ( $fields as $field ) {
                    $this->Assert( is_object( $field ), 'Item of array returned by DBTable->Fields was not an object' );
                    $this->Assert( $field instanceof DBField, 'Item of array returned by DBTable->Fields was not an instance of DBField' );
                }
            }
        }
        */
    }
    
    return New TestRabbitDb();
?>
