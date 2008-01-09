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
            $this->Assert( method_exists( $this->mFirstDatabase, 'Tables' ), 'Method Database->Tables() does\'t exist' );
			
			// DBTable
			$table = New DBTable();	
			$this->Assert( method_exists( $table, 'CreateField' ) , 'Method DBTable->CreateField() does\'t exist' );
			$this->Assert( method_exists( $table, 'CreateIndex' ) , 'Method DBTable->CreateIndex() does\'t exist' );
			
			// DBIndex
			$index = New DBIndex();	
			$this->Assert( method_exists( $index, 'AddField' ) ,  'Method DBIndex->CreateField() does\'t exist' );
			$this->Assert( method_exists( $index, 'CreateIndex' ) , 'Method DBIndex->CreateIndex() does\'t exist' );
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
					
		public function TestCreateTable() {
			$table = New DBTable();
			$table->Name = 'rabbit_test';

			$field = New DBField();
			$field->Name = 'user_id';
			$field->Type = DB_TYPE_INT;
			$field->IsAutoIncrement = true;

			$field2 = New DBField();
			$field2->Name = 'user_name';
			$field2->Type = DB_TYPE_CHAR;
			$field2->Length = 32;

			$field3 = New DBField();
			$field3->Name = 'user_subdomain';
			$field3->Type = DB_TYPE_CHAR;
			$field3->Length = 32;

			$table->CreateField( $field ); // DBField or array of DBField
			$table->CreateField( $field2 );

			$primary = New DBIndex();
			$primary->AddField( $field );
			$primary->Type = DB_KEY_PRIMARY;

			$username = New DBIndex();
			$username->AddField( $field2 );
			$username->Type = DB_KEY_UNIQUE;

			$subdomain = New DBIndex();
			$subdomain->AddField( $field2 ); // DBField or array of DBField
			$subdomain->AddField( $field3 ); // order matters
			$subdomain->Type = DB_KEY_INDEX;

			$table->CreateIndex( $primary ); // DBIndex or array of DBIndex
			$table->CreateIndex( $username );
			$table->CreateIndex( $subdomain );

			$table->Database = $this->mFirstDatabase;
            
            $this->mTestTable = New DBTable();
			
			$this->AssertFalse( $this->mTestTable->Exists(), 'Table must not exist prior to creation' );
			$table->Save();
			$this->mTestTable = $table;
			
			$this->AssertTrue( $this->mTestTable->Exists(), 'Table must exist after creation' );
		}
		
		public function TestDeleteTable() {
			$this->mTestTable->Delete();
			$this->AssertFalse( $this->mTestTable->Exists(), 'Table must not exist after deletion' );
		}
        public function TestTableByAlias() {
        }
    }
    
    return New TestRabbitDb();
?>
