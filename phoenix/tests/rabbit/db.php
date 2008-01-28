<?php
    class TestRabbitDbDriver implements DBDriver {
        public function LastAffectedRows( $link ) {
            return 12;
        }
        public function LastInsertId( $link ) {
            return 1337;
        }
        public function Query( $sql, $link ) {
            $sql = trim( $sql );
            $sql = explode( ' ', $sql );
            $sql = $sql[ 0 ];
            $sql = strtoupper( $sql );
            switch ( $sql ) {
                case 'SELECT':
                    return 'dbresource';
            }
        }
        public function SelectDb( $name, $link ) {}
        public function Connect( $host, $username, $password, $persist = true ) {
            return 'dblink';
        }
        public function LastErrorNumber( $link ) {
            return 0;
        }
        public function LastError( $link ) {
            return 'dberror';
        }
        public function NumRows( $driver_resource ) {
            return 3;
        }
        public function NumFields( $driver_resource ) {
            return 2;
        }
        public function FetchAssociativeArray( $driver_resource ) {}
        public function FetchField( $driver_resource, $offset ) {}
        public function GetName() {
            return 'TestRabbitDbDriver';
        }
		public function DataTypeByConstant( $constant ) {}
		public function ConstantByDataType( $datatype ) {}
        public function DataTypes() {}
        public function ConstructField( DBField $target, $info ) {}
    }
    
    class TestRabbitDb extends Testcase {
        protected $mAppliesTo = 'libs/rabbit/db/db';
        private $mDummyDb;
        private $mFirstDatabase;
		private $mTestTable;
        private $mField1;
        private $mField2;
        private $mField3;
        
        public function TestClassesExist() {
            $this->Assert( class_exists( 'Database' ), 'Class Database doesn\'t exist' );
            $this->Assert( interface_exists( 'DBDriver' ), 'Interface DBDriver doesn\'t exist' );
            $this->Assert( class_exists( 'DBField' ), 'Class DBField doesn\'t exist' );
            $this->Assert( class_exists( 'DBIndex' ), 'Class DBIndex doesn\'t exist' );
            $this->Assert( class_exists( 'DBTable' ), 'Class DBTable doesn\'t exist' );
            $this->Assert( class_exists( 'DBException' ), 'Class DBException doesn\'t exist' );
        }
        public function TestSettings() {
            global $rabbit_settings;
            
            $this->Assert( isset( $rabbit_settings[ 'databases' ] ), '"databases" setting not specified -- cannot continue testing without some databases to work on' );
            $this->Assert( is_array( $rabbit_settings[ 'databases' ] ), '"databases" setting is not an array -- cannot continue testing without some databases to work on' );
            $this->Assert( count( $rabbit_settings[ 'databases' ] ), '"databases" setting is empty -- cannot continue testing without some databases to work on' );
        }
        public function TestDatabase() {
            global $rabbit_settings;
            
            $this->mFirstDatabase = $GLOBALS[ reset( array_keys( $rabbit_settings[ 'databases' ] ) ) ];
            $this->Assert( $this->mFirstDatabase instanceof Database, 'Your first defined database does not appear to be consistent' );
            
            $driver = New TestRabbitDbDriver();
            $this->mDummyDb = New Database( 'test', $driver );
            $this->mDummyDb->Connect( 'example.org' );
            $this->mDummyDb->Authenticate( 'rabbit', 'secret' );
            $this->mDummyDb->SetCharset( 'UTF-8' );
        }
        public function TestMethodsExist() {
            $this->Assert( method_exists( $this->mDummyDb, 'Tables' ), 'Method Database->Tables() doesn\'t exist' );
            $this->Assert( method_exists( $this->mDummyDb, 'Connect' ), 'Method Database->Connect() doesn\'t exist' );
            $this->Assert( method_exists( $this->mDummyDb, 'Authenticate' ), 'Method Database->Authenticate() doesn\'t exist' );
            $this->Assert( method_exists( $this->mDummyDb, 'Equals' ), 'Method Database->Equals() doesn\'t exist' );
            $this->Assert( method_exists( $this->mDummyDb, 'SetCharset' ), 'Method Database->SetCharset() doesn\'t exist' );
            $this->Assert( method_exists( $this->mDummyDb, 'SwitchDb' ), 'Method Database->SwitchDb() doesn\'t exist' );
            
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
            
            $keys = array_keys( $rabbit_settings[ 'databases' ] );
            $key = reset( $keys );
            $database = reset( $rabbit_settings[ 'databases' ] );
            $this->Assert( is_string( $key ), 'Each database alias should be a string' );
            $this->Assert( isset( $database[ 'name' ] ), '"name" attribute is obligatory for all databases' );
            $this->Assert( isset( $database[ 'driver' ] ), '"driver" attribute is obligatory for all databases' );
            $this->Assert( isset( $database[ 'hostname' ] ), '"hostname" attribute is obligatory for all databases' );
            $this->Assert( isset( $GLOBALS[ $key ] ), 'Database was not imported into the global namespace' );
            $this->Assert( is_object( $GLOBALS[ $key ] ), 'Database imported into the global namespace was not an object' );
            $this->Assert( $GLOBALS[ $key ] instanceof Database, 'Database imported into the global namespace does not appear to be a Database instance' );
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
        public function TestDatabaseEquality() {
            $this->Assert( $this->mFirstDatabase->Equals( $this->mFirstDatabase ), 'A database should be equal to itself (1)' );
            $this->Assert( $this->mDummyDb->Equals( $this->mDummyDb ), 'A database should be equal to itself (2)' );
        }
        public function TestAttachTable() {
            $tables = $this->mDummyDb->Tables();
            $this->Assert( is_array( $tables ), 'Database->Tables() should return an array' );
            $this->AssertEquals( 0, count( $tables ), 'Number of tables attached is incorrect (should be 0)' );
            $this->mDummyDb->AttachTable( 'alias', 'actual' );
            $this->Assert( is_object( $this->mDummyDb->TableByAlias( 'alias' ) ), 'TableByAlias() should return an object for existing tables' );
            $this->Assert( $this->mDummyDb->TableByAlias( 'alias' ) instanceof DBTable, 'TableByAlias() should return a DBTable instance for existing tables' );
            $this->AssertEquals( 'actual', $this->mDummyDb->TableByAlias( 'alias' )->Name, 'Could not retrieve table by alias (1)' );
            $this->mDummyDb->AttachTable( 'hello', 'world' );
            $this->AssertEquals( 'world', $this->mDummyDb->TableByAlias( 'hello' )->Name, 'Could not retrieve table by alias (2)' );
            $this->AssertEquals( false, $this->mDummyDb->TableByAlias( 'foo' ), 'Non-existing tables should result in a false return value when calling TableByAlias on a Database instance (1)' );
            $this->AssertEquals( false, $this->mDummyDb->TableByAlias( 'world' ), 'Non-existing tables should result in a false return value when calling TableByAlias on a Database instance (2)' );
            $tables = $this->mDummyDb->Tables();
            $this->AssertEquals( 2, count( $tables ), 'Number of tables attached is incorrect (should be 2)' );
            $this->Assert( isset( $tables[ 'alias' ] ), 'Table "alias" could not be read' );
            $this->Assert( isset( $tables[ 'hello' ] ), 'Table "hello" could not be read' );
            $this->AssertEquals( 'actual', $tables[ 'alias' ]->Name, 'Table "alias" should have the name "actual"' );
            $this->AssertEquals( 'world', $tables[ 'hello' ]->Name, 'Table "hello" should have the name "world"' );
            $this->mDummyDb->DetachTable( 'alias' );
            $tables = $this->mDummyDb->Tables();
            $this->AssertEquals( 1, count( $tables ), 'Number of tables attached is incorrect (should be 1)' );
            $detachnonexisting = true;
            try {
                $this->mDummyDb->DetachTable( 'foo' );
            }
            catch ( DBException $e ) {
                $detachnonexisting = false;
            }
            $this->AssertFalse( $detachnonexisting, 'Attempts to detach a non-existing table should throw an exception' );
            $this->AssertEquals( 1, count( $tables ), 'Number of tables attached is incorrect (should be 1 again)' );
            $this->mDummyDb->DetachTable( 'hello' );
            $tables = $this->mDummyDb->Tables();
            $this->AssertEquals( 0, count( $tables ), 'Number of tables attached should be zero after detaching all existing tables' );
        }
        public function TestTableEquality() {
			$table = New DBTable();
            $this->Assert( $table->Equals( $table ), 'A new table must be equal to itself' );
            
            $this->mDummyDb->AttachTable( 'alias', 'actual' );
            $table = $this->mDummyDb->TableByAlias( 'alias' );
        }
		public function TestCreateTable() {
			$table = New DBTable();
			$this->AssertFalse( $table->Exists(), 'Table must not exist prior to creation' );
            $this->Assert( is_array( $table->Fields ), 'DBTable->Fields must be an array when creating a new table' );
            $this->AssertEquals( 0, count( $table->Fields ), 'No fields must exist before we add them to a new database table' );
            
			$table->Name = 'rabbit_test';
            $this->AssertEquals( 'rabbit_test', $table->Name, 'Table name could not be set' );
            
            $table->Alias = 'rabbit_test';
            $this->AssertEquals( 'rabbit_test', $table->Alias, 'Table alias could not be set' );
            
			$this->mField1 = New DBField();
            $this->AssertFalse( $this->mField1->Exists(), 'Field must not exist prior to creation' );
            $this->mField1->Name = 'user_id';
            $this->AssertEquals( 'user_id', $this->mField1->Name, 'Field name could not be set' );
			$this->mField1->Type = DB_TYPE_INT;
            $this->AssertEquals( DB_TYPE_INT, $this->mField1->Type, 'Field type could not be set to DB_TYPE_INT' );
			$this->mField1->IsAutoIncrement = true;
            $this->AssertEquals( true, $this->mField1->IsAutoIncrement, 'Field autoincrement could not be set' );

			$this->mField2 = New DBField();
			$this->mField2->Name = 'user_name';
			$this->mField2->Type = DB_TYPE_CHAR;
            $this->AssertEquals( DB_TYPE_CHAR, $this->mField2->Type, 'Field type could not be set to DB_TYPE_CHAR' );
			$this->mField2->Length = 32;
            $this->AssertEquals( 32, $this->mField2->Length, 'Field length could not be set' );
            
			$this->mField3 = New DBField();
			$this->mField3->Name = 'user_subdomain';
			$this->mField3->Type = DB_TYPE_CHAR;
			$this->mField3->Length = 32;

			$table->CreateField( $this->mField1 ); // DBField or array of DBField
			$table->CreateField( array( $this->mField2, $this->mField3 ) );
            
            $this->Assert( is_array( $table->Fields ), 'DBTable->Fields must contain the fields to be created, even prior to table creation' );
            $this->AssertEquals( 3, count( $table->Fields ), 'Created 3 fields, but they\'re not there' );
            $i = 0;
            foreach ( $table->Fields as $field ) {
                switch ( $i ) {
                    case 0:
                        $this->Assert( is_object( $field ), 'The field of the table must be an object (1)' );
                        $this->Assert( $field instanceof DBField, 'The field of the table must be a DBField (1)' );
                        $this->AssertEquals( $this->mField1, $field, 'Field1 was not in place' );
                        break;
                    case 1:
                        $this->Assert( is_object( $field ), 'The field of the table must be an object (2)' );
                        $this->Assert( $field instanceof DBField, 'The field of the table must be a DBField (2)' );
                        $this->AssertEquals( $this->mField2, $field, 'Field2 was not in place' );
                        break;
                    case 2:
                        $this->Assert( is_object( $field ), 'The field of the table must be an object (3)' );
                        $this->Assert( $field instanceof DBField, 'The field of the table must be a DBField (3)' );
                        $this->AssertEquals( $this->mField3, $field, 'Field3 was not in place' );
                        break;
                }
                ++$i;
            }
            
			$primary = New DBIndex();
            $this->AssertFalse( $primary->Exists(), 'Primary key must not exist prior to creation' );
            $this->Assert( is_array( $primary->Fields ), 'Index fields must be an array, even when no fields have been added yet' );
            $this->AssertEquals( 0, count( $primary->Fields ), 'Index fields must be the empty array when no fields have been added yet' );
			$primary->AddField( $this->mField1 );
            $this->AssertEquals( 1, count( $primary->Fields ), 'Could not add one field to an index' );
            $this->AssertEquals( $this->mField1, reset( $primary->Fields ), 'The field added to the index does not match the field specified' );
		 	$primary->Type = DB_KEY_PRIMARY;
            $this->AssertEquals( DB_KEY_PRIMARY, $primary->Type, 'Could not set key type to DB_KEY_PRIMARY' );

			$username = New DBIndex();
			$username->AddField( $this->mField2 );
			$username->Type = DB_KEY_UNIQUE;
            $this->AssertEquals( DB_KEY_UNIQUE, $username->Type, 'Could not set key type to DB_KEY_UNIQUE' );
            $username->Name = 'USER_UNIQUE';
            $this->AssertEquals( 'USER_UNIQUE', $username->Name, 'Could not set the name of a unique index' );

			$subdomain = New DBIndex();
			$subdomain->AddField( $this->mField2 ); // DBField or array of DBField
			$subdomain->AddField( $this->mField3 ); // order matters
            $this->AssertEquals( 2, count( $subdomain->Fields ), 'Could not create a multifield index' );
            $this->AssertEquals( $this->mField2, reset( $subdomain->Fields ), 'Multifield index must maintain field order (1)' );
            $this->AssertEquals( $this->mField3, next( $subdomain->Fields ), 'Multifield index must maintain field order (2)' );
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
            $this->Assert( is_object( $table ), 'Item of array returned by Database->Tables() was not an object' );
            $this->Assert( $table instanceof DBTable, 'Item of array returned by Database->Tables() was not an instance of DBTable' );
            $this->Assert( $table->Equals( $table2 ), 'Table returned by TableByAlias() must match table returned by listing (1)' );
            $this->Assert( $table2->Equals( $table ), 'Table returned by TableByAlias() must match table returned by listing (2)' );
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
                        $this->Assert( $this->mField1->ParentTable->Equals( $field->ParentTable ), 'The parent table of the same field should be the same' );
                        $this->AssertEquals( $this->mField1->Exists(), $field->Exists(), 'Identical fields should have identical existance values' );
                        $this->AssertEquals( $this->mField1->Length, $field->Length, 'Identical fields should have the same length' );
                        $this->AssertEquals( $this->mField1->IsAutoIncrement, $field->IsAutoIncrement, 'Identical fields should have identical autoincrement values' );
                        $this->AssertEquals( $this->mField1->Default, $field->Default, 'Identical fields should have identical default values' );
                        $this->AssertEquals( $this->mField1->Type, $field->Type, 'Identical fields should have identical types' );
                        $this->Assert( $this->mField1->Equals( $field ), 'The first column created must match the one read' );
                        $this->Assert( $field->Equals( $this->mField1 ), 'The first column created must match the one read' );
                        break;
                    case 1:
                        $this->AssertEquals( 'user_name', $field->Name, 'The second column must be "user_name"' );
                        $this->Assert( $this->mField2->ParentTable->Equals( $field->ParentTable ), 'The parent table of the same field should be the same (2)' );
                        $this->AssertEquals( $this->mField2->Exists(), $field->Exists(), 'Identical fields should have identical existance values (2)' );
                        $this->AssertEquals( $this->mField2->Length, $field->Length, 'Identical fields should have the same length (2)' );
                        $this->AssertEquals( $this->mField2->IsAutoIncrement, $field->IsAutoIncrement, 'Identical fields should have identical autoincrement values (2)' );
                        $this->AssertEquals( $this->mField2->Default, $field->Default, 'Identical fields should have identical default values (2)' );
                        $this->AssertEquals( $this->mField2->Type, $field->Type, 'Identical fields should have identical types (2)' );
                        $this->Assert( $this->mField2->Equals( $field ), 'The second column created must match the one read' );
                        break;
                    case 2:
                        $this->AssertEquals( 'user_subdomain', $field->Name, 'The third column must be "user_subdomain"' );
                        $this->Assert( $this->mField3->Equals( $field ), 'The third column created must match the one read' );
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
                switch ( $i ) {
                    case 0:
                        $this->AssertEquals( DB_KEY_PRIMARY, $index->Type, 'Could not read PRIMARY KEY type' );
                        $this->Assert( is_array( $index->Fields ), 'Primary key fields must be an array' );
                        $this->AssertEquals( 1, count( $index->Fields ), 'Incorrect number of fields defined for primary key' );
                        reset( $index->Fields );
                        $this->AssertEquals( current( $index->Fields ) instanceof DBField, 'Primary key field is not a field instance' );
                        $this->Assert( $this->mField1->Equals( current( $index->Fields ) ), 'Primary key field is not the expected one' );
                        break;
                    case 1:
                        $this->AssertEquals( DB_KEY_UNIQUE, $index->Type, 'Could not read UNIQUE KEY type' );
                        $this->Assert( is_array( $index->Fields ), 'Unique key fields must be an array' );
                        $this->AssertEquals( 1, count( $index->Fields ), 'Incorrect number of fields defined for unique key' );
                        reset( $index->Fields );
                        $this->Assert( current( $index->Fields ) instanceof DBField, 'Unique key field is not a field instance' );
                        $this->Assert( $this->mField2->Equals( current( $index->Fields ) ), 'Unique key field is not the expected one' );
                        break;
                    case 2:
                        $this->AssertEquals( DB_KEY_INDEX, $index->Type, 'Could not read INDEX KEY type' );
                        $this->Assert( is_array( $index->Fields ), 'Index key fields must be an array' );
                        $this->AssertEquals( 2, count( $index->Fields ), 'Incorrect number of fields defined for index key' );
                        reset( $index->Fields );
                        $this->Assert( current( $index->Fields ) instanceof DBField, 'Index key field is not a field instance (1)' );
                        $this->Assert( $this->mField2->Equals( current( $index->Fields ) ), 'Index key field is not the expected one (1)' );
                        next( $index->Fields );
                        $this->Assert( current( $index->Fields ) instanceof DBField, 'Index key field is not a field instance (2)' );
                        $this->Assert( $this->mField3->Equals( current( $index->Fields ) ), 'Index key field is not the expected one (2)' );
                        break;
                }
                ++$i;
            }
        }
		public function TestDeleteTable() {
			$this->mTestTable->Delete();
			$this->AssertFalse( $this->mTestTable->Exists(), 'Table must not exist after deletion' );
		}
    }
    
    return New TestRabbitDb();
?>
