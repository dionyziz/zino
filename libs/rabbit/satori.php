<?php
    abstract class Satori { // basic domain-level object class; all domain-level objects should extend this
        protected $mId; // most extensions will use this; some might not
        protected $mDb; // database object referring to the database where the object is stored
        protected $mDbTable; // table containing the object
        protected $mPreviousValues;
        protected $mExists; // whether the current object exists in the database
        private $mDbFields;
        private $mDbFieldKeys;
        private $mReadOnlyFields;
        
        public function __set( $name, $value ) {
            global $water;
            
            // check if a custom setter is specified
            $methodname = 'Set' . $name;
            if ( method_exists( $this, $methodname ) ) {
                $success = $this->$methodname( $value ); // MAGIC!
                if ( $success !== false ) {
                    return;
                }
                /* else fallthru */
            }
            
            if ( !in_array( $name, $this->mDbFields ) ) {
                $water->Warning( 'Attempting to write non-existing Satori property `' . $name . '\' on a `' . get_class( $this ) . '\' instance' );
                return;
            }
            
            if ( isset( $this->mReadOnlyFields[ $name ] ) ) {
                $water->Warning( 'Attempting to write read-only Satori attribute `' . $name . '\' on a `' . get_class( $this ) . '\' instance' );
                return;
            }
            
            $varname = 'm' . $name;
            $this->$varname = $value; // MAGIC!
        }
        public function __get( $name ) {
            global $water;
            
            // check if a custom getter is specified
            $methodname = 'Get' . $name;
            if ( method_exists( $this, $methodname ) ) {
                return $this->$methodname(); // MAGIC!
            }
            
            if ( !in_array( $name, $this->mDbFields ) ) {
                $water->Warning( 'Attempting to read non-existing Satori property `' . $name . '\' on a `' . get_class( $this ) . '\' instance' );
                return;
            }
            $varname = 'm' . $name;
            
            return $this->$varname; // MAGIC!
        }
        public function __isset( $name ) {
            return in_array( $name, $this->mDbFields );
        }
        public function __unset( $name ) {
            global $water;
            
            $water->Warning( 'Attempting to unset Satori property on a `' . get_class( $this ) . '\' instance; Satori properties cannot be unset' );
        }
        protected function WhereAmI() { 
            // return an identifier that can be used along with WHERE to detect current entry in DB
            // normally, `foo_id` = 5, but overloadable in case the particular table doesn't use an auto-increment primary key
            return '`' . reset( $this->mDbFieldKeys ) . '` = ' . $this->mId;
        }
        protected function MakeReadOnly( /* $name1 [, $name2 [, ...]] */ ) {
            // make a member attribute read-only; this doesn't have any impact for custom setters
            global $water;
            
            $args = func_get_args();
            w_assert( count( $args ) );
            foreach ( $args as $name ) {
                if ( !in_array( $name, $this->mDbFields ) ) {
                    $water->Warning( 'Attempting to convert non-existing Satori property `' . $name . '\' to read-only on a `' . get_class( $this ) . '\' instance' );
                    return;
                }
                $this->mReadOnlyFields[ $name ] = true;
            }
        }
        protected function MakeWritable( /* $name1 [, $name2 [, ...]] */ ) {
            // turn a member attribute that was previously changed to read-only into writable again
            global $water;
            
            $args = func_get_args();
            w_assert( count( $args ) );
            foreach ( $args as $name ) {
                if ( !in_array( $name, $this->mDbFields ) ) {
                    $water->Warning( 'Attempting to convert non-existing Satori property `' . $name . '\' to read-write on a `' . get_class( $this ) . '\' instance' );
                    return;
                }
                unset( $this->mReadOnlyFields[ $name ] );
            }
        }
        public function Exists() {
            // check if the current object exists; this can be overloaded if you wish, but you can also set $this->mExists
            return $this->mId > 0;
        }
        public function Save() {
            if ( $this->Exists() ) {
                $sql = 'UPDATE
                            `' . $this->mDbTable . '`
                        SET
                            ';
                $updates = array();
                foreach ( $this->mDbFields as $fieldname => $attributename ) {
                    $varname = 'm' . $attributename;
                    $attributevalue = $this->$varname; // MAGIC!
                    if ( !isset( $this->mPreviousValues[ $attributename ] ) ) {
                        echo $attributename . "\n";
                        die( var_dump( $this->mPreviousValues ) );
                    }
                    if ( $this->mPreviousValues[ $attributename ] != $attributevalue ) {
                        $updates[] = '`' . $fieldname . '` = "' . myescape( $attributevalue ) . "\"\n";
                        $this->mPreviousValues[ $attributename ] = $attributevalue;
                    }
                }
                if ( !count( $updates ) ) {
                    return true;
                }
                
                $sql .= implode( ', ', $updates );
                $sql .= 'WHERE ' . $this->WhereAmI() . ' LIMIT 1;';
                return $this->mDb->Query( $sql );
            }
            else {
                $inserts = array();
                foreach ( $this->mDbFields as $fieldname => $attributename ) {
                    $varname = 'm' . $attributename;
                    $attributevalue = $this->$varname; // MAGIC!
                    $inserts[ $fieldname ] = $attributevalue;
                    $this->mPreviousValues[ $attributename ] = $attributevalue;
                }
                $change = $this->mDb->Insert(
                    $inserts, $this->mDbTable
                );
                if ( $change->Impact() ) {
                    $this->mId = $change->InsertId();
                }
                $this->mExists = true;
                return $change;
            }
        }
        public function Delete() {
            w_assert( $this->Exists() );
            
            $sql = 'DELETE FROM
                        `' . $this->mDbTable . '`
                    WHERE ' . $this->WhereAmI() . ' LIMIT 1;';
            $this->mExists = false;
            return $this->mDb->Query( $sql );
        }
        protected function SetFields( $dbfields ) {
            w_assert( is_array( $dbfields ) );
            w_assert( count( $dbfields ) );
            
            $this->mDbFields = $dbfields;
            $this->mDbFieldKeys = array_keys( $this->mDbFields );
            
            foreach ( $this->mDbFields as $fieldname => $attributename ) {
                w_assert( is_string( $fieldname ) );
                w_assert( preg_match( '#^[a-zA-Z0-9_\-]+$#', $fieldname ) );
                w_assert( is_string( $attributename ) );
                w_assert( preg_match( '#^[a-zA-Z][a-zA-Z0-9]*$#', $attributename ) );
                
                $varname = 'm' . $attributename;
                // default value
                $this->$varname = false; // MAGIC!
            }
            
            if ( reset( $this->mDbFields ) == 'Id' ) {
                $this->MakeReadOnly( 'Id' );
            }
        }
        protected function LoadDefaults() {
            // overload me
        }
        protected function Satori( $construct = false ) {
            global $water;
            
            w_assert( is_string( $this->mDbTable ), 'Please specify your database table by setting member attribute mDbTable for class `' . get_class( $this ) . '\'' );
            w_assert( preg_match( '#^[a-zA-Z0-9_\-]+$#', $this->mDbTable ), 'Your table name is incorrect; make sure you have specified a valid database table name for class `' . get_class( $this ) . '\'' );
            w_assert( $this->mDb instanceof Database, 'Please specify your database by setting member attribute mDb for class `' . get_class( $this ) . '\' to a valid database instance' );
            w_assert( is_array( $this->mDbFields ), 'Please specify your database fields by calling SetFields() for class `'. get_class( $this ) . '\'' );
            
            if ( $construct === false ) {
                // empty new object
                // set defaults
                $this->mExists = false;
                $this->LoadDefaults();
                $fetched_array = array();
            }
            else if ( ValidId( $construct ) ) {
                $this->mId = $construct; // overload constructor in case of ValidId() if you don't use Ids
                $sql = 'SELECT
                            ' . implode( ',', $this->mDbFieldKeys ) . '
                        FROM 
                            `' . $this->mDbTable . '`
                        WHERE ' . $this->WhereAmI() . ' LIMIT 1';
                $res = $this->mDb->Query( $sql );
                if ( $res->NumRows() != 1 ) {
                    $this->mExists = false;
                    $fetched_array = array();
                }
                else {
                    $this->mExists = true;
                    $fetched_array = $res->FetchArray();
                }
            }
            else {
                if ( !is_array( $construct ) ) {
                    $water->ThrowException( 'Satori construction must be done either voidly, by an Id integer, or by a fetched array. `' . $construct . '\' is not a valid construction parameter.' );
                }
                $fetched_array = $construct;
                $this->mExists = count( $fetch_array ) > 0;
            }
            
            foreach ( $this->mDbFields as $fieldname => $attributename ) {
                if ( isset( $fetched_array[ $fieldname ] ) ) {
                    $varname = 'm' . $attributename;
                    $this->$varname = $fetched_array[ $fieldname ]; // MAGIC!
                    $this->mPreviousValues[ $attributename ] = $fetched_array[ $fieldname ];
                }
            }
        }
    }
?>
