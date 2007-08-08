<?php
	/*
		OOP MySQL Database Layer
		Developer: dionyziz
	*/
	
	class Database {
		protected $mDbName;
		protected $mHostname;
		protected $mUsername;
		protected $mPassword;
		protected $mPort;
		protected $mLink;
		protected $mCharSet;
		protected $mCharSetApplied;
		protected $mConnected;
		
		public function Database( $dbname = '' ) {
			$this->mDbName = $dbname;
			$this->mConnected = false;
			$this->mCharSetApplied = true;
			$this->mCharSet = false;
		}
		public function Connect( $hostname = 'localhost' , $port = 3306 ) {
			$this->mHostname = $hostname;
			$this->mPort = $port;
			
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
        public function Hostname() {
            return $this->mHostname;
        }
        public function Port() {
            return $this->mPort;
        }
        public function SwitchDb( $dbname ) {
            global $water;
            
            $this->mDbName = $dbname;
            if ( $this->mConnected ) {
                if ( $this->mDbName != '' ) {
                    $selection = mysql_select_db( $this->mDbName, $this->mLink );
                    if ( $selection === false ) {
                        $water->Warning( 'Failed to select the specified database:<br />' . mysql_error( $this->mLink ) );
                        return false;
                    }
                }
            }
            return true;
        }
		protected function ActualConnect() {
			global $water;
			
			if ( !$this->mConnected ) {
				$this->mLink = mysql_connect( $this->mHostname , $this->mUsername , $this->mPassword , false );
				if ( $this->mLink === false ) {
					$water->Warning( 'Connection to MySQL failed:<br />' . mysql_error( $this->mLink ) );
					return false;
				}
                $this->mConnected = true;
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
				$this->Query( 'SET NAMES ' . $this->mCharSet );
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
			$res = mysql_query( $sql , $this->mLink );
			$water->LogSQLEnd();
			if ( $res === false && mysql_errno() > 0 ) {
				$water->ThrowException( 'MySQL failed' , array( 'query' => $sql , 'error' => mysql_errno( $this->mLink ) . ': ' . mysql_error( $this->mLink ) ) );
                return false;
			}
			else if ( $res === true ) {
				return New DBChange( $this );
			}
			return New DBResource( $res );
		}
		public function Insert( $inserts , $table , $ignore = false , $delayed = false , $quota = 500 ) {
			// $table = 'table';
			// $table = array( 'database' , 'table' )
			// $insert = array( field1 => value1 , field2 => value2 , ... );
			// ->Insert( $insert , $table );
			// ->Insert( array( $insert1 , $insert2 , ... ) , $table );
			
			w_assert( !( $ignore && $delayed ) );
			
			if ( is_array( $table ) ) {
				w_assert( count( $table ) == 2 );
				w_assert( preg_match( '/^[a-zA-Z0-9_\-]+$/' , $table[ 0 ] ) );
				w_assert( preg_match( '/^[a-zA-Z0-9_\-]+$/' , $table[ 1 ] ) );
				$table = '`' . $table[ 0 ] . '`.`' . $table[ 1 ] . '`';
			}
			else {
				// assert correct table name
				w_assert( preg_match( '/^[\.`a-zA-Z0-9_\-]+$/' , $table ) );
				$table = '`' . $table . '`';
			}
			
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
						// escape each value before inserting
						$insert[ $field ] = myescape( $value );
					}
					// implode into a value list (value1, value2, value3, ...)
					$insertvalues[] = '\'' . implode( '\', \'' , $insert ) . '\'';
				}
				w_assert( count( $insertvalues ) );
				$sql = "$insertinto
							$table
						(`" . implode( '`, `' , $fields ) . "`) VALUES
						(" . implode( '), (' , $insertvalues ) . ");"; // implode all value lists into (list1), (list2), ...
				$changes[] = $this->Query( $sql );
			}
			if ( !$multipleinserts ) {
				return end( $changes ); // only return the one and only single item of $changes
			}
			return $changes; // return an array of change
		}
		public function Tables() {
			$res = $this->Query( 'SHOW TABLES FROM `' . $this->mDbName . '`' );
			$rows = array();
			while ( $row = $res->FetchArray() ) {
				$rows[] = New DBTable( $this , array_shift( $row ) );
			}
			return $rows;
		}
		public function Link() {
			return $this->mLink;
		}
	}

	class DBChange {
		protected $mAffectedRows;
		protected $mDb;
		protected $mInsertId;
		
		public function DBChange( $db ) {
			$this->mDb = $db;
			$this->mAffectedRows = mysql_affected_rows( $db->Link() );
			$this->mInsertId = mysql_insert_id( $db->Link() );
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
		protected $mNumRows;
		protected $mNumFields;
		
		public function DBResource( $sqlresource ) {
			$this->mSQLResource = $sqlresource;
			$this->mNumRows = mysql_num_rows( $sqlresource );
			$this->mNumFields = mysql_num_fields( $sqlresource );
		}
		protected function SQLResource() {
			return $this->mSQLResource;
		}
		public function FetchArray() {
			return mysql_fetch_array( $this->mSQLResource , MYSQL_ASSOC );
		}
		public function FetchField( $num ) {
			return mysql_fetch_field( $this->mSQLResource, $num );
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
	
	class DBTable {
		protected $mDb;
		protected $mTableName;
		
		public function DBTable( $db , $tablename ) {
			$this->mDb = $db;
			$this->mTableName = $tablename;
		}
		public function Name() {
			return $this->mTableName;
		}
		public function Truncate() {
			return $this->mDb->Query( 'TRUNCATE `' . $this->mTableName . '`;' );
		}
	}
?>
