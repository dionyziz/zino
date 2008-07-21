<?php
	// Define database data types
    define( 'DB_TYPE_INT' 		, 'DB_TYPE_INT' );
    define( 'DB_TYPE_VARCHAR' 	, 'DB_TYPE_VARCHAR' );
    define( 'DB_TYPE_CHAR' 		, 'DB_TYPE_CHAR' );
    define( 'DB_TYPE_TEXT' 		, 'DB_TYPE_TEXT' );
    define( 'DB_TYPE_LONGTEXT'  , 'DB_TYPE_LONGTEXT' );
    define( 'DB_TYPE_DATETIME'	, 'DB_TYPE_DATETIME' );
    define( 'DB_TYPE_DATE'	    , 'DB_TYPE_DATE' );
    define( 'DB_TYPE_FLOAT'		, 'DB_TYPE_FLOAT' );
    define( 'DB_TYPE_ENUM'		, 'DB_TYPE_ENUM' );
	
    class DBField {
        protected $mValidDataTypes = array(
            DB_TYPE_INT,
            DB_TYPE_VARCHAR,
            DB_TYPE_CHAR,
            DB_TYPE_TEXT,
            DB_TYPE_LONGTEXT,
            DB_TYPE_DATETIME,
            DB_TYPE_DATE,
            DB_TYPE_FLOAT,
            DB_TYPE_ENUM
        );
        protected $mName;
        protected $mType;
		protected $mLength;
        protected $mDefault;
		protected $mExists;
        protected $mNull;
		protected $mStoredState;
        protected $mIsAutoIncrement;
        protected $mParentTable;
        
        public function Exists() {
            return $this->mExists;
        }
		public function __get( $key ) {
			switch ( $key ) {
				 case 'Name':
				 case 'Type':
				 case 'Default':
				 case 'Length':
				 case 'Null':
				 case 'IsPrimaryKey':
				 case 'IsAutoIncrement':
				 case 'ParentTable':
				 	$attribute = 'm' . $key;
					return $this->$attribute;
				 case 'SQL':
					// returns a string representation of the field as it would be used within a CREATE or 
					// ALTER query

					$sql = "`" . $this->Name . "` ";
					$sql .= ":_" . $this->Type . " "; // autobound

					if ( !empty( $this->mLength ) ) {   
						$sql .= "(" . $this->mLength . ")";
					}
					$sql .= " ";
					if ( !$this->Null ) {
						$sql .= "NOT NULL ";
					}
					if ( !empty( $this->mDefault ) ) {
						$sql .= "DEFAULT " . $this->mDefault . " ";
					}
					if ( $this->IsAutoIncrement ) {
						$sql .= "AUTO_INCREMENT";
					}
					return $sql;
			}
		}
		public function __set( $key, $value ) {
			switch ( $key ) {
				case 'Name':
					w_assert( is_string( $value ), 'Database field name specified is invalid' );
					$this->mName = $value;
					break;
				case 'Type':
					if ( !in_array( $value, $this->mValidDataTypes ) ) {
						throw New DBException( 'Database field data type specified is invalid' );
					}
					$this->mType = $value;
					if ( $this->mLength === false ) {
						// no length specified, use default lengths
						switch ( $this->mType ) {
							case DB_TYPE_INT:
								$this->Length = 11;
								break;
						}
					}
					break;
				case 'Default':
					w_assert( is_scalar( $value ), 'Non-scalar value set as default value for database field' );
					$this->mDefault = $value;
					break;
				case 'Length':
					w_assert( is_int( $value ) );
					$this->mLength = $value;
					break;
				case 'Null':
					w_assert( is_bool( $value ), 'Database field can only be null or not null (bool)' );
					$this->mNull = $value;
					break;
				case 'IsAutoIncrement':
					w_assert( is_bool( $value ) );
					$this->mIsAutoIncrement = $value;
					break;
				case 'ParentTable':
					// called by table save
            		$this->mParentTable = $value;
					break;
				case 'Exists':
					// called by table creation method
					w_assert( is_bool( $value ) );
					w_assert( $value === true );
					$this->mExists = $value;
					break;
				default:
					parent::__set( $key, $value );
			}
        }
        public function Equals( DBField $target ) {
            if ( !is_object( $target ) ) {
                return false;
            }
            return
                   $this->Exists() == $target->Exists()
                && $this->Name == $target->Name
                && $this->Length == $target->Length
                && $this->IsAutoIncrement == $target->IsAutoIncrement
                && $this->Default == $target->Default
                && $this->Type == $target->Type
                && $this->ParentTable->Equals( $target->ParentTable );
        }
        public function CastValueToNativeType( $value ) {
            switch ( $this->mType ) {
                case DB_TYPE_INT:
                    return ( int )$value;
                case DB_TYPE_FLOAT:
                    return ( float )$value;
                case DB_TYPE_DATETIME:
                case DB_TYPE_DATE:
                case DB_TYPE_ENUM:
                case DB_TYPE_VARCHAR:
                case DB_TYPE_CHAR:
                case DB_TYPE_TEXT:
                case DB_TYPE_LONGTEXT:
                    return ( string )$value;
                default:
                    throw New DBException( 'Invalid DB datatype: ' . $this->mType );
            }
        }
        public function DBField( $parenttable = false, $info = false ) {
            $this->mLength = false;
            $this->mIsAutoIncrement = false;
            $this->mDefault = '';
            $this->mNull = false;
            if ( $info === false && $parenttable === false ) {
                $this->mExists = false;
            }
            else {
                $this->mExists = true;
                w_assert( is_object( $parenttable ) );
                w_assert( $parenttable instanceof DBTable );
                $this->mParentTable = $parenttable;
                $this->mParentTable->Database->ConstructField( $this, $info );
            }
        }
        public function __toString() {
            return ( string )$this->mParentTable . '.`' . $this->mName . '`';
        }
    }
?>
