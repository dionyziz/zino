<?php
	/*
		Generalized OOP Database Layer
		Developer: dionyziz
	*/
	
	global $libs;
	
	
		
	// Define database data types
    define( 'DB_TYPE_INT' 		, 1 );
    define( 'DB_TYPE_VARCHAR' 	, 2 );
    define( 'DB_TYPE_CHAR' 		, 3 );
    define( 'DB_TYPE_TEXT' 		, 4 );
    define( 'DB_TYPE_DATETIME'	, 5 );
    define( 'DB_TYPE_FLOAT'		, 6 );
    define( 'DB_TYPE_ENUM'		, 7 );
	
	// Define database index types
	define( 'DB_KEY_INDEX'		, 1 );
	define( 'DB_KEY_UNIQUE'		, 2 );
	define( 'DB_KEY_PRIMARY'	, 3 );
    
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
    }
    
    $libs->Load( 'rabbit/db/mysql' ); // load mysql support
    
	class Database {
		protected $mDbName;
		protected $mHost;
		protected $mUsername;
		protected $mPassword;
		protected $mLink;
		protected $mCharSet;
		protected $mCharSetApplied;
		protected $mConnected;
		protected $mDriver;
        protected $mTables;
        
		public function Database( $dbname = false, $driver = false ) {
            if ( $driver === false ) {
                $this->mDriver = New DatabaseDriver_MySQL();
            }
            else {
                $this->mDriver = $driver;
            }
            w_assert( $this->mDriver instanceof DatabaseDriver );
            w_assert( $dbname === false || is_string( $dbname ) ); // false because you can SwitchDb() later
			$this->mDbName = $dbname;
			$this->mConnected = false;
			$this->mCharSetApplied = true;
			$this->mCharSet = false;
            $this->mTables = false;
		}
		public function Connect( $host = 'localhost' ) {
			$this->mHost = $host;
            
			return true;
		}
		public function Authenticate( $username , $password ) {
			$this->mUsername = $username;
			$this->mPassword = $password;
			
			return true;
		}
        public function Name() {
            return $this->mDbName;
        }
        public function Host() {
            return $this->mHost;
        }
        public function Port() {
            return $this->mPort;
        }
        public function SwitchDb( $dbname ) {
            global $water;
            
            $this->mDbName = $dbname;
            if ( $this->mConnected ) {
                if ( $this->mDbName != '' ) {
                    $selection = $this->mDriver->SelectDb( $this->mDbName, $this->mLink );
                    if ( $selection === false ) {
                        $water->Warning( "Failed to select the specified database:\n" . $this->mDriver->LastError( $this->mLink ) );
                        return false;
                    }
                }
            }
            return true;
        }
		protected function ActualConnect() {
			global $water;
			
			if ( !$this->mConnected ) {
				$this->mLink = $this->mDriver->Connect( $this->mHost , $this->mUsername , $this->mPassword , false );
				if ( $this->mLink === false ) {
					$water->Warning( "Connection to database failed:\n" . $this->mDriver->LastError( $this->mLink ) );
					return false;
				}
                $this->mConnected = true;
                if ( empty( $this->mDbName ) ) {
                    return true;
                }
                return $this->SwitchDb( $this->mDbName );
			}
			return false;
		}
		public function SetCharset( $charset ) {
			if ( $this->mCharSet !== $charset ) {
				$this->mCharSet = $charset;
				$this->mCharSetApplied = false;
			}
		}
		private function CharSetApply() {
			if ( !$this->mCharSetApplied ) {
				$this->mCharSetApplied = true;
				$this->Prepare( 'SET NAMES ' . $this->mCharSet )->Execute(); // TODO: this is only compatible with MySQL?
			}
		}
		public function Query( $sql ) {
			global $water;
			
			$this->ActualConnect(); // lazy connect
			if ( !$this->mConnected ) {
				$water->Warning( 'Could not execute SQL query because no SQL connection was found' , $sql );
				return false;
			}
			$this->CharSetApply();
			if ( $water->Enabled() ) {
				$backtrace = debug_backtrace();
				$lasttrace = array_shift( $backtrace );
				if ( strpos( $lasttrace[ 'file' ] , '/elements/' ) ) {
					$water->Warning( 'Potential database call from element!' );
				}
				if ( strpos( $lasttrace[ 'file' ] , '/units/' ) ) {
					$water->Warning( 'Potential database call from unit!' );
				}
			}
			$water->LogSQL( $sql );
			$res = $this->mDriver->Query( $sql , $this->mLink );
			$water->LogSQLEnd();
			if ( $res === false ) {
				throw New Exception( 'Database query failed' , array( 'query' => $sql , 'error' => $this->mDriver->LastErrorNumber( $this->mLink ) . ': ' . $this->mDriver->LastError( $this->mLink ) ) );
			}
			else if ( $res === true ) {
				return New DBChange( $this->mDriver, $this->mLink );
			}
			return New DBResource( $res, $this->mDriver );
		}
        public function Prepare( $rawsql ) {
            return New DBQuery( $rawsql, $this );
        }
		public function Insert( $inserts , $tablealias , $ignore = false , $delayed = false , $quota = 500 ) {
			// $table = 'table';
			// $table = array( 'database' , 'table' )
			// $insert = array( field1 => value1 , field2 => value2 , ... );
			// ->Insert( $insert , $table );
			// ->Insert( array( $insert1 , $insert2 , ... ) , $table );
			
			w_assert( !( $ignore && $delayed ) );
			
			// assert correct table name
            $table = $this->TableByAlias( $tablealias );
			w_assert( $table instanceof DBTable );

			// assert at least one insert statement; or at least one field
			w_assert( count( $inserts ) );
			// if doing only one direct insert, call self with that special case;
			// keep in mind that in single inserts, the values of the array must be scalar
			// while in multiple inserts, the values are arrays of scalar values
			if ( !is_array( end( $inserts ) ) ) {
				$inserts = array( $inserts );
				$multipleinserts = false;
			}
			else {
				$multipleinserts = true;
			}
			
			if ( $ignore ) {
				$insertinto = 'INSERT IGNORE INTO';
			}
			else if ( $delayed ) {
				$insertinto = 'INSERT DELAYED INTO';
			}
			else {
				$insertinto = 'INSERT INTO';
			}
			
			// get last insert to get the fields of the insert
			$lastinsert = end( $inserts );
			$fields = array();
			// build fields list (only once)
			foreach ( $lastinsert as $field => $value ) {
				// assert correct field names
				w_assert( preg_match( '/^[a-zA-Z0-9_\-]+$/' , $field ) );
				$fields[] = $field;
			}
			// assert there is at least one field
			w_assert( count( $fields ) );
			// return value will be an array of change structures
			$changes = array();
			// split insert into 500's, for speed and robustness; this also limits the chance of getting out of query
			// size bounds
			for ( $i = 0 ; $i < count( $inserts ) ; $i += $quota ) {
				$partinserts = array_slice( $inserts , $i , $quota );
				w_assert( count( $partinserts ) );
				$insertvalues = array();
				foreach ( $partinserts as $insert ) {
					reset( $fields );
					foreach ( $insert as $field => $value ) {
						// assert the fields are the same number and in the same order in each insert
						$thisfield = each( $fields );
						w_assert( $thisfield[ 'value' ] == $field );
					}
					$insertvalues[] = $insert;
				}
				w_assert( count( $insertvalues ) );
                $bindings = array();
                $i = 0;
                foreach ( $insertvalues as $valuetuple ) {
                    $bindings[] = ':insert' . $i;
                    ++$i;
                }
                
				$query = $this->Prepare(
                    "$insertinto
						:$tablealias
					(`" . implode( '`, `' , $fields ) . "`) VALUES
					" . implode( ',', $bindings ) . ";"
                ); // implode all value lists into (list1), (list2), ...
                $i = 0;
                foreach ( $insertvalues as $valuestuple ) {
                    $query->Bind( substr( $bindings[ $i ], 1 ), $valuestuple );
                    ++$i;
                }
                $query->BindTable( $tablealias );
				$changes[] = $query->Execute();
			}
			if ( !$multipleinserts ) {
				return end( $changes ); // only return the one and only single item of $changes
			}
			return $changes; // return an array of change
		}
        public function AttachTable( $alias, $actual ) {
            w_assert( preg_match( '#^[\.a-zA-Z0-9_\-]+$#', $alias ), 'Invalid database table alias `' . $alias . '\'' );
            $this->mTables[ $alias ] = New DBTable( $this, $actual, $alias );
        }
        public function DetachTable( $alias, $actual ) {
            w_assert( preg_match( '#^[\.a-zA-Z0-9_\-]+$#', $alias ), 'Invalid database table alias `' . $alias . '\'' );
            w_assert( isset( $this->mTables[ $alias ] ), 'Cannot detach a table that has not been attached yet' );
            unset( $this->mTables[ $alias ] );
        }
        public function TableByAlias( $alias ) {
            if ( !isset( $this->mTables[ $alias ] ) ) {
                return false;
            }
            return $this->mTables[ $alias ];
        }
		public function Tables() {
            return $this->mTables;
		}
		public function Link() {
			return $this->mLink;
		}
	}

    class DBQuery {
        protected $mRawSQL; // doesn't contain binded arguments
        protected $mBindings;
        protected $mDatabase;
        
        public function DBQuery( $raw, Database $database ) {
            w_assert( is_string( $raw ), 'Cannot prepare SQL query with a non-string SQL statement' );
            w_assert( !empty( $raw ), 'Cannot prepare SQL query with an empty SQL statement' );
            
            $this->mRawSQL = $raw;
            $this->mDatabase = $database;
            $this->mBindings = array();
            $this->mTableBindings = array();
        }
        private function Escape( $argument ) {
            switch ( gettype( $argument ) ) {
                case 'boolean':
                    return ( int )$argument;
                case 'integer':
                case 'double':
                    return $argument;
                case 'array':
                    return '(' . implode( ',', array_map( array( $this, 'Escape' ), $argument) ) . ')'; // RECURSE!
                default:
                    return "'" . addslashes( ( string )$argument ) . "'";
            }
        }
        public function Bind( $name, $argument ) {
            $this->mBindings[ ':' . ( string )$name ] = $this->Escape( $argument );
        }
        public function BindTable( /* $alias1, $alias2, ... */ ) {
            global $water;
            
            $args = func_get_args();
            w_assert( count( $args ), 'Binding a table requires at least one argument containing a table alias' );
            
            foreach ( $args as $alias ) {
                w_assert( is_string( $alias ), 'Database table aliases must be strings' );
                w_assert( strlen( $alias ), 'Database table aliases cannot be the empty string' );
                $table = $this->mDatabase->TableByAlias( $alias );
                if ( $table === false ) {
                    $water->Warning( 'Could not bind database table `' . $alias . '`' );
                    return;
                }
                $this->mTableBindings[ ':' . $alias ] = '`' . $table->Name . '`';
            }
        }
        public function Apply() {
            w_assert( !empty( $this->mRawSQL ), 'Cannot apply bindings to an empty SQL statement' );
            
            return strtr( $this->mRawSQL, array_merge( $this->mBindings, $this->mTableBindings ) );
        }
        public function Execute() {
            $applied = $this->Apply();
            w_assert( !empty( $applied ), 'Cannot execute empty SQL query' );
            
            return $this->mDatabase->Query( $applied );
        }
    }
    
	class DBChange {
		protected $mAffectedRows;
		protected $mInsertId;
		
		public function DBChange( DatabaseDriver $driver, $driver_link ) {
			$this->mAffectedRows = $driver->LastAffectedRows( $driver_link );
			$this->mInsertId = $driver->LastInsertId( $driver_link );
		}
		public function AffectedRows() {
			return $this->mAffectedRows;
		}
		public function Impact() {
			return $this->mAffectedRows > 0;
		}
		public function InsertId() {
			return $this->mInsertId;
		}
	}

	class DBResource {
		protected $mSQLResource;
        protected $mDriver;
		protected $mNumRows;
		protected $mNumFields;
		
		public function DBResource( $sqlresource, DatabaseDriver $driver ) {
            $this->mDriver = $driver;
			$this->mSQLResource = $sqlresource;
			$this->mNumRows = $this->mDriver->NumRows( $sqlresource );
			$this->mNumFields = $this->mDriver->NumFields( $sqlresource );
		}
		protected function SQLResource() {
			return $this->mSQLResource;
		}
		public function FetchArray() {
			return $this->mDriver->FetchAssociativeArray( $this->mSQLResource );
		}
		public function FetchField( $offset ) {
			return $this->mDriver->FetchField( $this->mSQLResource, $offset );
		}
		public function MakeArray() {
			$i = 0;
			$ret = array();
			while ( $row = $this->FetchArray() ) {
				foreach ( $row as $key => $value ) {
					$ret[ $i ][ $key ] = $value;
				}
				++$i;
			}
			
			return $ret;
		}
        public function ToObjectsArray( $class ) {
            $ret = array();
            while ( $row = $this->FetchArray() ) {
                $ret[] = New $class( $row ); // MAGIC!
            }

            return $ret;
        }
		public function NumRows() {
			return $this->mNumRows;
		}
		public function NumFields() {
			return $this->mNumFields;
		}
		public function Results() {
			return $this->NumRows() > 0;
		}
	}
	
	class DBTable extends Overloadable {
		protected $mDb;
		protected $mTableName;
		protected $mAlias;
        protected $mFields;
		protected $mExists;
        
        protected function GetName() {
            return $this->mTableName;
        }
        protected function GetAlias() {
            return $this->mAlias;
        }
        protected function GetFields() {
            if ( $this->mFields === false ) {
                $query = $this->mDb->Prepare( 
                    'SHOW FIELDS FROM :' . $this->mAlias . ';'
                );
                $query->BindTable( $this->mAlias );
                $res = $query->Execute();
                $this->mFields = array();
                while ( $row = $res->FetchArray() ) {
                    $this->mFields[] = New DBField( $row );
                }
            }
            return $this->mFields;
        }
		public function DBTable( $db = false, $tablename = false, $alias = '' ) {
            if ( $db === false ) { 
				w_assert( $alias === '', 'No aliases should be passed for new DB tables' );
			}
			else {
				w_assert( is_object( $db ), 'Database passed to DBTable must be an object' );
				w_assert( $db instanceof Database, 'Database passed to DBTable must be an instance of Database ( ' . get_class( $db ) .' given ) ' );
				w_assert( is_string( $alias ), 'Database table alias `' . $alias . '\' is not a string' );
	            w_assert( is_string( $tablename ), 'Database table name `' . $tablename . '\' is not a string' );
	            w_assert( preg_match( '#^[\.a-zA-Z0-9_\-]*$#', $alias ), 'Database table alias `' . $alias . '\' is invalid' );
	            w_assert( preg_match( '#^[\.a-zA-Z0-9_\-]+$#', $tablename ), 'Database table name `' . $tablename . '\' is invalid' );
				$this->mDb = $db;
				$this->mTableName = $tablename;
			}
			$this->mAlias = $alias;
			$this->mFields = false;
		}
		public function Truncate() {
			$query = $this->mDb->Prepare( 'TRUNCATE :' . $this->mAlias . ';' );
            $query->BindTable( $this->mAlias );
            return $query->Execute();
		}
        public function CreateField( $field ) {
            w_assert( $field instanceof DBField );
        }
        public function CreateIndex( $index ) {
            w_assert( $index instanceof DBIndex );
        }
        public function Save() {
        }
        public function Delete() {
        }
        public function Exists() {
            return $this->mDb instanceof Database && !empty( $this->mTableName );
        }
	}
    
    class DBField extends Overloadable {
        protected $mName;
        protected $mType;
		protected $mLength;
		protected $mExists;
		protected $mStoredState;
        protected $mIsPrimaryKey;
        protected $mIsAutoIncrement;

        protected function GetIsAutoIncrement() {
            return $this->mIsAutoIncrement;
        }
        protected function GetName() {
            return $this->mName;
        }
        protected function GetType() {
            return $this->mType;
        }
		protected function SetType( $type ) {
			w_assert( is_int( $type ), 'Database field data type specified is invalid' );
			// TODO: add assert,  $type must be valid!!
			$this->mType = $type;
		}
		protected function SetName( $name ) {
			w_assert( is_string( $name ), 'Database field name specified is invalid' );
			$this->mName = $name;
			
		} 
        public function DBField( $info ) {
            $this->mName = $info[ 'Field' ];
            $this->mType = $info[ 'Type' ];
            $this->mIsPrimaryKey = $info[ 'Key' ] == 'PRI';
            $this->mIsAutoIncrement = $info[ 'Extra' ] == 'auto_increment';
        }
    }

    class DBIndex extends Overloadable {
        public function AddField() {
        }
        public function CreateIndex() {
        }
        public function DBIndex() {
        }
    }

?>
