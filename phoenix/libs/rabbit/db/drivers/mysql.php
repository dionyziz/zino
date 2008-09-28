<?php
	/*
		MySQL Database Driver for Rabbit (default)
		Developer: Dionyziz
	*/
	
	class DBDriver_MySQL implements DBDriver {
		// Rabbit DB constant to native mysql type descriptor
		private $mDataTypes = array( 
			DB_TYPE_INT		 => 'INT',
			DB_TYPE_VARCHAR	 => 'VARCHAR',
			DB_TYPE_CHAR		=> 'CHAR',
			DB_TYPE_TEXT		=> 'TEXT',
			DB_TYPE_LONGTEXT	=> 'LONGTEXT',
			DB_TYPE_DATETIME	=> 'DATETIME',
			DB_TYPE_DATE		=> 'DATE',
			DB_TYPE_FLOAT		=> 'FLOAT',
			DB_TYPE_ENUM		=> 'ENUM'
		); 
		private $mFlippedDataTypes = false;
		
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
			return mysql_connect( $host, $username, $password );
		}
		public function LastErrorNumber( $driver_link ) {
			return mysql_errno( $driver_link );
		}
		public function LastError( $driver_link = false ) {
			if ( $driver_link !== false ) {
				return mysql_error( $driver_link );
			}
			return mysql_error();
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
		public function DataTypeByConstant( $constant ) {
			return $this->mDataTypes[ $constant ]; 
		}
		public function ConstantByDataType( $datatype ) {
			if ( $this->mFlippedDataTypes === false ) {
				$this->mFlippedDataTypes = array_flip( $this->mDataTypes );
				// FLOAT synonyms or similars
				$this->mFlippedDataTypes[ 'DECIMAL' ] = DB_TYPE_FLOAT;
				$this->mFlippedDataTypes[ 'DEC' ] = DB_TYPE_FLOAT;
				$this->mFlippedDataTypes[ 'DOUBLE' ] = DB_TYPE_FLOAT;
				// INT synonyms or similars
				$this->mFlippedDataTypes[ 'TINYINT' ] = DB_TYPE_INT;
				$this->mFlippedDataTypes[ 'BOOL' ] = DB_TYPE_INT;
				$this->mFlippedDataTypes[ 'BOOLEAN' ] = DB_TYPE_INT;
				$this->mFlippedDataTypes[ 'SMALLINT' ] = DB_TYPE_INT;
				$this->mFlippedDataTypes[ 'MEDIUMINT' ] = DB_TYPE_INT;
				$this->mFlippedDataTypes[ 'INTEGER' ] = DB_TYPE_INT;
				$this->mFlippedDataTypes[ 'BIGINT' ] = DB_TYPE_INT;
				$this->mFlippedDataTypes[ 'LONGINT' ] = DB_TYPE_INT;
				// ENUM synonyms or similars
				$this->mFlippedDataTypes[ 'SET' ] = DB_TYPE_ENUM;
				// CHAR synonyms or similars
				$this->mFlippedDataTypes[ 'BINARY' ] = DB_TYPE_CHAR;
				// VARCHAR synonyms or similars
				$this->mFlippedDataTypes[ 'VARBINARY' ] = DB_TYPE_VARCHAR;
				// TEXT
				$this->mFlippedDataTypes[ 'TINYBLOB' ] = DB_TYPE_TEXT;
				$this->mFlippedDataTypes[ 'TINYTEXT' ] = DB_TYPE_TEXT;
				$this->mFlippedDataTypes[ 'BLOB' ] = DB_TYPE_TEXT;
				$this->mFlippedDataTypes[ 'MEDIUMBLOB' ] = DB_TYPE_TEXT;
				$this->mFlippedDataTypes[ 'MEDIUMTEXT' ] = DB_TYPE_TEXT;
				// LONGTEXT
				$this->mFlippedDataTypes[ 'LONGBLOB' ] = DB_TYPE_LONGTEXT;
			}
			if ( isset( $this->mFlippedDataTypes[ $datatype ] ) ) {
				return $this->mFlippedDataTypes[ $datatype ];
			}
			throw New DBException( 'Invalid data type specified for field: ' . $datatype );
		}
		public function DataTypes() {
			return $this->mDataTypes;
		}
		public function ConstructField( DBField $field, $info ) {
			w_assert( isset( $info[ 'Type' ] ) );
			w_assert( isset( $info[ 'Field' ] ) );
			
			$field->Name = $info[ 'Field' ];
			$type = strtoupper( $info[ 'Type' ] );
			if ( strpos( $type, '(' ) !== false ) {
				$typeinfo = explode( '(', $type );
				$type = $typeinfo[ 0 ];
				$typeinfo[ 1 ] = str_replace( ')', '', $typeinfo[ 1 ] );
				$length = $typeinfo[ 1 ];
				$type = trim( $type );
				$length = trim( $length );
				$length = ( int )$length;
				$field->Length = $length;
			}
			$field->Type = $this->ConstantByDataType( $type );
			$field->IsAutoIncrement = $info[ 'Extra' ] == 'auto_increment';
			if ( isset( $info[ 'Default' ] ) ) {
				$field->Default = $info[ 'Default' ];
			}
			else {
				$field->Default = '';
			}
		}
	}
?>
