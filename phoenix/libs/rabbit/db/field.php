<?php
    class DBField extends Overloadable {
        protected $mName;
        protected $mType;
		protected $mLength;
        protected $mDefault;
		protected $mExists;
        protected $mNull;
		protected $mStoredState;
        protected $mIsPrimaryKey;
        protected $mIsAutoIncrement;
        protected $mParentTable;
        
        public function Exists() {
            return $this->mExists;
        }
        protected function GetName() {
            return $this->mName;
        }
        protected function GetType() {
            return $this->mType;
        }
        protected function GetDefault() {
            return $this->mDefault;
        }
        protected function GetLength() {
            return $this->mLength;
        }
        protected function GetNull() {
            return $this->mNull;
        }
        protected function GetIsPrimaryKey() {
            return $this->mIsPrimaryKey;
        }
        protected function GetIsAutoIncrement() {
            return $this->mIsAutoIncrement;
        }
		protected function SetName( $name ) {
			w_assert( is_string( $name ), 'Database field name specified is invalid' );
			$this->mName = $name;
		}
		protected function SetType( $type ) {
			// w_assert( is_int( $type ), 'Database field data type specified is invalid' );
			// TODO: add assert,  $type must be valid!!
			$this->mType = $type;
		}
        protected function SetDefault( $value ) {
            w_assert( is_scalar( $value ), 'Non-scalar value set as default value for database field' );
            $this->mDefault = $value;
        }
        protected function SetLength( $value ) {
            w_assert( is_int( $value ) );
            $this->mLength = $value;
        }
        protected function SetNull( $value ) {
            w_assert( is_bool( $value ), 'Database field can only be null or not null (bool)' );
            $this->mNull = $value;
        }
        protected function SetIsPrimaryKey( $value ) {
            w_assert( is_bool( $value ) );
            $this->mIsPrimaryKey = $value;
        }
        protected function SetIsAutoIncrement( $value ) {
            w_assert( is_bool( $value ) );
            $this->IsAutoIncrement = $value;
        }
        public function GetSQL() {
            // returns a string representation of the field as it would be used within a CREATE or 
            // ALTER query
            $sql = "`" . $this->Name . "` ";
            $sql .= ":_" . $this->Type . " "; // autobound

            if ( !empty( $this->Length ) ) {   
                $sql .= "(" . $this->Length . ")";
            }
            $sql .= " ";
            if ( !$this->Null ) {
                $sql .= "NOT NULL ";
            }
            if ( !empty( $this->Default ) ) {
                $sql .= "DEFAULT " . $this->Default . " ";
            }
            if ( $this->IsAutoIncrement ) {
                $sql .= "AUTO_INCREMENT";
            }
            return $sql;
        }
        public function DBField( $parenttable = false, $info = false ) {
            if ( $info === false && $parenttable === false ) {
                $this->mExists = false;
            }
            else {
                $this->mExists = true;
                w_assert( is_object( $parenttable ) );
                w_assert( $parenttable instanceof DBTable );
                $this->mParentTable = $parenttable;
                $this->mName = $info[ 'Field' ];
                $this->mType = $info[ 'Type' ];
                $this->mIsPrimaryKey = $info[ 'Key' ] == 'PRI';
                $this->mIsAutoIncrement = $info[ 'Extra' ] == 'auto_increment';
                $this->mDefault = $info[ 'Default' ];
            }
        }
    }
?>
