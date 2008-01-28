<?php
    /*
        MySQL Database Driver for Rabbit (default)
        Developer: Dionyziz
    */
    
    class DBDriver_MySQL implements DBDriver {
        // Rabbit DB constant to native mysql type descriptor
		private $mDataTypes = array( 
			DB_TYPE_INT 		=> 'INT',
			DB_TYPE_VARCHAR 	=> 'VARCHAR',
			DB_TYPE_CHAR		=> 'CHAR',
			DB_TYPE_TEXT		=> 'TEXT',
			DB_TYPE_DATETIME	=> 'DATETIME',
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
		public function DataTypeByConstant( $constant ) {
			return $this->mDataTypes[ $constant ]; 
		}
		public function ConstantByDataType( $datatype ) {
			if ( $this->mFlippedDataTypes === false ) {
				$this->mFlippedDataTypes = array_flip( $this->mDataTypes );
			}
            if ( isset( $this->mFlippedDataTypes[ $datatype ] ) ) {
    			return $this->mFlippedDataTypes[ $datatype ];
            }
            return false;
		}
        public function DataTypes() {
            return $this->mDataTypes;
        }
        public function ConstructField( DBField $field, $info ) {
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
                $field->Default = false;
            }
        }
    }
    
?>
