<?php
	// Define database index types
	define( 'DB_KEY_INDEX'		, 1 );
	define( 'DB_KEY_UNIQUE'		, 2 );
	define( 'DB_KEY_PRIMARY'	, 3 );
    
    class DBIndex extends Overloadable {
        private $mExists;
        private $mFields;
        private $mName;
        private $mType;
        private $mCardinality;
        private $mParentTable;
        
        public function Exists() {
            return $this->mExists;
        }
        public function AddField( DBField $field ) {
            w_assert( is_object( $field ) );
            $this->mFields[] = $field;
        }
        protected function GetFields() {
            return $this->mFields;
        }
        public function Save() {
            global $water;
            
            if ( $this->mExists ) {
                $water->Warning( 'Cannot create DBIndex; DBIndex already exists' );
                return;
            }
            w_assert( $this->mParentTable->Exists() );
            $query = $this->mParentTable->Database->Prepare( 
                'ALTER TABLE :' . $this->mParentTable->Alias . ' ADD ' . $this->SQL
            );
            $query->BindTable( $this->mParentTable->Alias );
            $query->Execute();
        }
        public function GetSQL() {
            $sql = '';
            if ( $this->mType == DB_KEY_PRIMARY ) {
                $sql .= 'PRIMARY KEY ';
            }
            else {
                if ( $this->mType == DB_KEY_UNIQUE ) {
                    $sql .= 'UNIQUE ';
                }
                $sql .= 'INDEX ';
            }
            $fields = array();
            foreach ( $this->mFields as $field ) {
                $fields[] = $field->Name;
            }
            $sql .= $this->mName;
            $sql .= ' (`' . implode('`, `', $fields) . '`)';
            return $sql;
        }
        protected function GetType() {
            return $this->mType;
        }
        protected function GetName() {
            return $this->mName;
        }
        protected function SetType( $type ) {
            switch ( $type ) {
                case DB_KEY_INDEX:
                case DB_KEY_UNIQUE:
                case DB_KEY_PRIMARY:
                    $this->mType = $type;
                    return;
                default:
                    throw New DBException( 'Invalid index type `' . $type . '\'' );
            }
        }
        protected function SetName( $name ) {
            $this->mName = $name;
        }
        protected function SetParentTable( DBTable $table ) {
            $this->mParentTable = $table;
        }
        protected function GetCardinality() {
            return $this->mCardinality;
        }
        protected function SetExists( $value ) {
            w_assert( is_bool( $value ) );
            w_assert( $value === true );
            $this->mExists = $value;
        }
        public function Unserialize( $info ) {
            w_assert( is_array( $info ) );
            w_assert( count( $info ) );
            
            $firstfield = $info[ 0 ];
            
            w_assert( isset( $firstfield[ 'Key_name' ] ) );
            w_assert( isset( $firstfield[ 'Non_unique' ] ) );
            if ( isset( $firstfield[ 'Cardinality' ] ) ) {
                $this->mCardinality = $firstfield[ 'Cardinality' ];
            }
            $this->mName = $firstfield[ 'Key_name' ];
            if ( $firstfield[ 'Non_unique' ] == 1 ) {
                $this->mType = DB_KEY_INDEX;
            }
            else if ( $firstfield[ 'Key_name' ] == 'PRIMARY' ) {
                $this->mType = DB_KEY_PRIMARY;
            }
            else {
                $this->mType = DB_KEY_UNIQUE;
            }
            foreach ( $info as $field ) {
                $this->mFields[ ( int )$field[ 'Seq_in_index' ] - 1 ] = $this->mParentTable->FieldByName( $field[ 'Column_name' ] );
            }
        }
        public function Serialize() {
            $info = array();
            
            $i = 0;
            foreach ( $this->mFields as $field ) {
                $info[ $i ][ 'Key_name' ] = $this->mName;
                $info[ $i ][ 'Cardinality' ] = $this->mCardinality;
                if ( $this->mType = DB_KEY_INDEX ) {
                    $info[ $i ][ 'Non_unique' ] = 1;
                }
                $info[ $i ][ 'Seq_in_index' ] = $i + 1;
                $info[ $i ][ 'Column_name' ] = $field;
                ++$i;
            }
            
            return $info;
        }
        public function DBIndex( $parenttable = false, $info = false ) {
            $this->mFields = array();
            if ( $info === false && $parenttable === false ) {
                $this->mExists = false;
            }
            else {
                $this->mExists = true;
                w_assert( is_object( $parenttable ) );
                w_assert( $parenttable instanceof DBTable );
                w_assert( $parenttable->Exists() );
                $this->mParentTable = $parenttable;
                
                $this->Unserialize( $info );
            }
        }
    }
?>
