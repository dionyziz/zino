<?php
    class TestRabbitDb extends Testcase {
        protected $mAppliesTo = 'libs/rabbit/db/db';
        private $mFirstDatabase;
		private $mTestTable;
        
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
            global $rabbit_settings;
            
            $this->mFirstDatabase = $GLOBALS[ reset( array_keys( $rabbit_settings[ 'databases' ] ) ) ];
            $this->Assert( $this->mFirstDatabase instanceof Database, 'Your first defined database does not appear to be consistent' );
            $this->Assert( method_exists( $this->mFirstDatabase, 'Tables' ), 'Method Database->Tables() doesn\'t exist' );
			
			// DBTable
			$table = New DBTable();	
			$this->Assert( method_exists( $table, 'CreateField' ) , 'Method DBTable->CreateField() doesn\'t exist' );
			$this->Assert( method_exists( $table, 'CreateIndex' ) , 'Method DBTable->CreateIndex() doesn\'t exist' );
            $this->Assert( method_exists( $table, 'Exists' ), 'Method DBTable->Exists() doesn\'t exist' );
			
			// DBIndex
			$index = New DBIndex();	
			$this->Assert( method_exists( $index, 'AddField' ) ,  'Method DBIndex->CreateField() doesn\'t exist' );
			$this->Assert( method_exists( $index, 'Save' ) , 'Method DBIndex->Save() doesn\'t exist' );
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
		public function TestCreateTable() {
			$table = New DBTable();
			$this->AssertFalse( $table->Exists(), 'Table must not exist prior to creation' );

			$table->Name = 'rabbit_test';
            $this->AssertEquals( 'rabbit_test', $table->Name, 'Table name could not be set' );
            
            $table->Alias = 'rabbit_test';
            $this->AssertEquals( 'rabbit_test', $table->Alias, 'Table alias could not be set' );
            
			$field = New DBField();
            $this->AssertFalse( $field->Exists(), 'Field must not exist prior to creation' );
            $field->Name = 'user_id';
            $this->AssertEquals( 'user_id', $field->Name, 'Field name could not be set' );
			$field->Type = DB_TYPE_INT;
            $this->AssertEquals( DB_TYPE_INT, $field->Type, 'Field type could not be set to DB_TYPE_INT' );
			$field->IsAutoIncrement = true;
            $this->AssertEquals( true, $field->IsAutoIncrement, 'Field autoincrement could not be set' );

			$field2 = New DBField();
			$field2->Name = 'user_name';
			$field2->Type = DB_TYPE_CHAR;
            $this->AssertEquals( DB_TYPE_CHAR, $field2->Type, 'Field type could not be set to DB_TYPE_CHAR' );
			$field2->Length = 32;
            $this->AssertEquals( 32, $field2->Length, 'Field length could not be set' );
            
			$field3 = New DBField();
			$field3->Name = 'user_subdomain';
			$field3->Type = DB_TYPE_CHAR;
			$field3->Length = 32;

			$table->CreateField( $field ); // DBField or array of DBField
			$table->CreateField( array( $field2, $field3 ) );

			$primary = New DBIndex();
            $this->AssertFalse( $primary->Exists(), 'Primary key must not exist prior to creation' );
            $this->Assert( is_array( $primary->Fields ), 'Index fields must be an array, even when no fields have been added yet' );
            $this->AssertEquals( 0, count( $primary->Fields ), 'Index fields must be the empty array when no fields have been added yet' );
			$primary->AddField( $field );
            $this->AssertEquals( 1, count( $primary->Fields ), 'Could not add one field to an index' );
            $this->AssertEquals( $field, reset( $primary->Fields ), 'The field added to the index does not match the field specified' );
		 	$primary->Type = DB_KEY_PRIMARY;
            $this->AssertEquals( DB_KEY_PRIMARY, $primary->Type, 'Could not set key type to DB_KEY_PRIMARY' );

			$username = New DBIndex();
			$username->AddField( $field2 );
			$username->Type = DB_KEY_UNIQUE;
            $this->AssertEquals( DB_KEY_UNIQUE, $username->Type, 'Could not set key type to DB_KEY_UNIQUE' );
            $username->Name = 'USER_UNIQUE';
            $this->AssertEquals( 'USER_UNIQUE', $username->Name, 'Could not set the name of a unique index' );

			$subdomain = New DBIndex();
			$subdomain->AddField( $field2 ); // DBField or array of DBField
			$subdomain->AddField( $field3 ); // order matters
            $this->AssertEquals( 2, count( $subdomain->Fields ), 'Could not create a multifield index' );
            $this->AssertEquals( $field2, reset( $subdomain->Fields ), 'Multifield index must maintain field order (1)' );
            $this->AssertEquals( $field3, next( $subdomain->Fields ), 'Multifield index must maintain field order (2)' );
            $subdomain->Name = 'USER_UNIQUE_SUBDOMAIN';
            $this->AssertEquals( 'USER_UNIQUE_SUBDOMAIN', $subdomain->Name, 'Could not set the name of an index prior to specifying its type' );
			$subdomain->Type = DB_KEY_INDEX;
            $this->AssertEquals( DB_KEY_INDEX, $subdomain->Type, 'Could not set key type to DB_KEY_INDEX' );

			$table->CreateIndex( $primary ); // DBIndex or array of DBIndex
			$table->CreateIndex( array( $username, $subdomain ) );

			$table->Database = $this->mFirstDatabase;
            $this->AssertEquals( $this->mFirstDatabase, $table->Database, 'Could not set table database' );
            
            try {
                $table->Save();
            }
            catch ( Exception $e ) {
                $this->Assert( false, 'Failed to execute table creation query' );
            }
            
			$this->mTestTable = $table;
			
			$this->AssertTrue( $this->mTestTable->Exists(), 'Table must exist after creation' );
		}
        public function TestTableList() {
            $tables = $this->mFirstDatabase->Tables();
            $this->Assert( is_array( $tables ), 'Value returned by Database->Tables() must be an array' );
            $this->Assert( count( $tables ), 'Could not list the table that was just created -- no tables were returned' );
            $found = false;
            foreach ( $tables as $table ) {
                if ( $table->Name == 'rabbit_test' ) {
                    $found = true;
                    $table2 = $table;
                    break;
                }
            }
            $this->Assert( $found, 'Could not find the recently created table within the list of tables' );
            $table = $this->mFirstDatabase->TableByAlias( 'rabbit_test' );
            
            $this->Assert( $table !== false, 'Could not find recently created table using TableByAlias() on the parent database' );
            $this->AssertEquals( $table, $table2, 'Table returned by TableByAlias() must match table returned by listing' );
            $this->Assert( is_object( $table ), 'Item of array returned by Database->Tables() was not an object' );
            $this->Assert( $table instanceof DBTable, 'Item of array returned by Database->Tables() was not an instance of DBTable' );
            $fields = $table->Fields;
            $this->Assert( is_array( $fields ), 'Value of attribute DBTable->Fields must be an array' );
            $this->AssertEquals( 3, count( $fields ), 'The number of fields of the recently created table is incorrect' );
            $i = 0;
            foreach ( $fields as $field ) {
                $this->Assert( is_object( $field ), 'Item of array returned by DBTable->Fields was not an object' );
                $this->Assert( $field instanceof DBField, 'Item of array returned by DBTable->Fields was not an instance of DBField' );
                switch ( $i ) {
                    case 0:
                        $this->AssertEquals( 'user_id', $field->Name, 'The first column must be "user_id"' );
                        break;
                    case 1:
                        $this->AssertEquals( 'user_name', $field->Name, 'The second column must be "user_name"' );
                        break;
                    case 2:
                        $this->AssertEquals( 'user_subdomain', $field->Name, 'The third column must be "user_subdomain"' );
                        break;
                }
                ++$i;
            }
            $indexes = $table->Indexes;
            $this->Assert( is_array( $indexes ), 'Value of attribute DBTable->Indexes must be an array' );
            $this->AssertEquals( 3, count( $indexes ), 'The number of indexes of the recently created table is incorrect' );
            $i = 0;
            foreach ( $indexes as $index ) {
                $this->Assert( is_object( $index ), 'Item of array returned by DBTable->Indexes was not an object' );
                $this->Assert( $index instanceof DBIndex, 'Item of array returned by DBTable->Indexes was not an instance of DBIndex' );
                ++$i;
            }
        }
		public function TestDeleteTable() {
			$this->mTestTable->Delete();
			$this->AssertFalse( $this->mTestTable->Exists(), 'Table must not exist after deletion' );
		}
		public function TestConstantsExist() {
			// Database data types
			$this->Assert( defined( 'DB_TYPE_DATETIME' )	, 'Constant of database type DATETIME must be defined' );
			$this->Assert( defined( 'DB_TYPE_VARCHAR' )		, 'Constant of database type VARCHAR must be defined' );
			$this->Assert( defined( 'DB_TYPE_ENUM' )		, 'Constant of database type ENUM must be defined' );
			$this->Assert( defined( 'DB_TYPE_CHAR' )		, 'Constant of database type CHAR must be defined' );
			$this->Assert( defined( 'DB_TYPE_INT' )			, 'Constant of database type INT must be defined' );
			$this->Assert( defined( 'DB_TYPE_TEXT' )		, 'Constant of database type TEXT must be defined' );
			$this->Assert( defined( 'DB_TYPE_FLOAT' )		, 'Constant of database type FLOAT must be defined' );
			
			// Database key index types
			$this->Assert( defined( 'DB_KEY_INDEX' )	, 'Constant of database key INDEX must be defined' );
			$this->Assert( defined( 'DB_KEY_UNIQUE' )	, 'Constant of database key UNIQUE must be defined' );
			$this->Assert( defined( 'DB_KEY_PRIMARY' )	, 'Constant of database key PRIMARY must be defined' );
        }
        public function TestTableByAlias() {
        }
    }
    
    return New TestRabbitDb();
?>
