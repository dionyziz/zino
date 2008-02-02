<?php
    /*
        Developer: Dionyziz
    */
    
    abstract class Overloadable {
        public function __set( $name, $value ) {
            // check if a custom setter is specified
            $methodname = 'Set' . $name;
            if ( method_exists( $this, $methodname ) ) {
                $success = call_user_func( array( $this, $methodname ), $value );
                if ( $success !== false ) {
                    return true;
                }
            }
            // else fallthru
            return false;
        }
        public function __get( $name ) {
            // check if a custom getter is specified
            $methodname = 'Get' . $name;
            if ( method_exists( $this, $methodname ) ) {
                $value = call_user_func( array( $this, $methodname ) );
                return $value;
            }
            // else fallthru
            return null; // use null here because we want to allow custom getters to return literal boolean false
        }
    }
    
    class SatoriException extends Exception {
    }
    
    // Active Record Base
    abstract class Satori extends Overloadable {
        protected $mDb; // database object referring to the database where the object is stored
        protected $mDbName; // name of the database we'll use for this object (defaults to your first database)
        protected $mDbTableAlias; // database table alias this object is mapped from
        protected $mDbTable; // DBTable instance for the database table this object is mapped from
        protected $mExists; // whether the current object exists in the database (this is false if a new object is created before it is saved in the database)
        private $mDbFields; // dictionary with full database field name (string) => class attribute name
        private $mDbFieldKeys; // list with database fields (string)
        private $mReadOnlyFields; // dictionary with class attributes => true
        private $mDbColumns; // dictionary with field name (string) => DBField instance
        private $mDbIndexes; // list with DBIndex instances
        private $mPrimaryKeyFields; // list with database fields that are primary keys (array of string)
        protected $mPreviousValues; // stores the persistent state of this object (i.e. the stored-in-the-database version)
        protected $mCurrentValues; // stores the current state of this object (i.e. the active state that will be saved into the database upon the issue of ->Save())
        protected $mAutoIncrementField; // string name of the database field that is autoincrement, or false if there is no autoincrement field
        protected $mDefaultValues; // dictionary with attribute name (string) => default value, to be used if value of empty object remains at 'false'

        public function __set( $name, $value ) {
            if ( parent::__set( $name, $value ) === true ) {
                return;
            }

            if ( !in_array( $name, $this->mDbFields ) ) {
                throw New SatoriException( 'Attempting to write non-existing Satori property `' . $name . '\' on a `' . get_class( $this ) . '\' instance' );
            }
            
            if ( isset( $this->mReadOnlyFields[ $name ] ) ) {
                throw New SatoriException( 'Attempting to write read-only Satori attribute `' . $name . '\' on a `' . get_class( $this ) . '\' instance' );
            }
            
            $this->mCurrentValues[ ucfirst( $name ) ] = $value;
        }
        public function __get( $name ) {
            if ( !is_null( $got = parent::__get( $name ) ) ) {
                return $got;
            }

            if ( !in_array( $name, $this->mDbFields ) ) {
                throw New SatoriException( 'Attempting to read non-existing Satori property `' . $name . '\' on a `' . get_class( $this ) . '\' instance' );
            }
            return $this->mCurrentValues[ ucfirst( $name ) ];
        }
        public function __isset( $name ) {
            return in_array( $name, $this->mDbFields );
        }
        public function __unset( $name ) {
            throw New SatoriException( 'Attempting to unset Satori property on a `' . get_class( $this ) . '\' instance; Satori properties cannot be unset' );
        }
        protected function MakeReadOnly( /* $name1 [, $name2 [, ...]] */ ) {
            // make a member attribute read-only; this doesn't have any impact for custom setters
            $args = func_get_args();
            w_assert( count( $args ) );
            foreach ( $args as $name ) {
                if ( !in_array( $name, $this->mDbFields ) ) {
                    throw New SatoriException( 'Attempting to convert non-existing Satori property `' . $name . '\' to read-only on a `' . get_class( $this ) . '\' instance' );
                }
                $this->mReadOnlyFields[ $name ] = true;
            }
        }
        protected function MakeWritable( /* $name1 [, $name2 [, ...]] */ ) {
            // turn a member attribute that was previously changed to read-only into writable again
            $args = func_get_args();
            w_assert( count( $args ) );
            foreach ( $args as $name ) {
                if ( !in_array( $name, $this->mDbFields ) ) {
                    throw New SatoriException( 'Attempting to convert non-existing Satori property `' . $name . '\' to read-write on a `' . get_class( $this ) . '\' instance' );
                }
                unset( $this->mReadOnlyFields[ $name ] );
            }
        }
        public function Exists() {
            // check if the current object exists; this can be overloaded if you wish, but you can also set $this->mExists
            return $this->mExists;
        }
        protected function GetPrimaryKeyFields() {
            return $this->mPrimaryKeyFields;
        }
        public function Save() {
            if ( $this->Exists() ) {
                $sql = 'UPDATE
                            :' . $this->mDbTableAlias . '
                        SET
                            ';
                $updates = array();
                $bindings = array();
                foreach ( $this->mDbFields as $fieldname => $attributename ) {
                    $attributevalue = $this->mCurrentValues[ $attributename ];
                    if ( $this->mPreviousValues[ $attributename ] != $attributevalue ) {
                        $updates[] = "`$fieldname` = :$fieldname";
                        $bindings[ $fieldname ] = $attributevalue;
                        $this->mPreviousValues[ $attributename ] = $attributevalue;
                    }
                }
                if ( !count( $updates ) ) {
                    // nothing to update
                    return true;
                }
                
                $sql .= implode( ', ', $updates );
                $sql .= ' WHERE ';
                $conditions = array();
                foreach ( $this->PrimaryKeyFields as $primarykey ) {
                    $conditions[] = '`' . $primarykey . '` = :_' . $primarykey;
                }
                $sql .= implode( ' AND ', $conditions );
                $sql .= ' LIMIT 1;';
                $query = $this->mDb->Prepare( $sql );
                $query->BindTable( $this->mDbTableAlias );
                foreach ( $this->mPrimaryKeyFields as $primarykeyfield ) {
                    $query->Bind( '_' . $primarykey, $this->mCurrentValues[ $this->mDbFields[ $primarykeyfield ] ] );
                }
                foreach ( $bindings as $name => $value ) {
                    $query->Bind( $name, $value );
                }
                $change = $query->Execute();
                return $change;
            }
            else {
                $inserts = array();
                foreach ( $this->mDbFields as $fieldname => $attributename ) {
                    $inserts[ $fieldname ] = $this->mCurrentValues[ $attributename ];
                    $this->mPreviousValues[ $attributename ] = $this->mCurrentValues[ $attributename ];
                }
                $change = $this->mDbTable->InsertInto( $inserts );
                if ( $change->Impact() ) {
                    if ( $this->mAutoIncrementField !== false ) {
                        $this->mCurrentValues[ $this->mDbFields[ $this->mAutoIncrementField ] ] = $change->InsertId();
                    }
                }
                $this->mExists = true;
                return $change;
            }
        }
        public function Delete() {
            if ( !$this->Exists() ) {
                throw New SatoriException( 'Cannot delete non-existing Satori object' );
            }
            
            $sql = 'DELETE FROM
                        :' . $this->mDbTableAlias . '
                    WHERE ';
            $conditions = array();
            foreach ( $this->PrimaryKeyFields as $primary ) {
                $conditions[] = '`' . $primary . '` = :' . $primary;
            }
            $sql .= implode( ' AND ', $conditions );
            $sql .= ' LIMIT 1';
            $query = $this->mDb->Prepare( $sql );
            $i = 0;
            foreach ( $this->PrimaryKeyFields as $primary ) {
                $query->Bind( $primary, $this->mCurrentValues[ $this->mDbFields[ $primary ] ] );
                ++$i;
            }
            $query->BindTable( $this->mDbTableAlias );
            
            $this->mExists = false;
            return $query->Execute();
        }
        protected function InitializeFields() {
            if ( !( $this->mDb instanceof Database ) ) {
                throw New SatoriException( 'Database not specified or invalid for Satori class `' . get_class( $this ). '\'' );
            }
            
            $this->mDbTable = $this->mDb->TableByAlias( $this->mDbTableAlias );
            
            if ( !( $this->mDbTable instanceof DBTable ) ) {
                throw New SatoriException( 'Database table not specified or invalid for Satori class `' . get_class( $this ) . '\'' );
            }
            
            $this->mDbColumns = array();
            foreach ( $this->mDbTable->Fields as $field ) {
                $this->mDbColumns[ $field->Name ] = $field;
            }
            if ( !count( $this->mDbColumns ) ) {
                throw New SatoriException( 'Database table `' . $this->mDbTableAlias . '\' used for Satori class `' . get_class( $this ) . '\' does not have any columns' );
            }
            $this->mDbIndexes = $this->mDbTable->Indexes;
            if ( !count( $this->mDbIndexes ) ) {
                throw New SatoriException( 'Database table `' . $this->mDbTableAlias . '\' used for Satori class `' . get_class( $this ) . '\' does not have any keys (primary key required)' );
            }
            
            $this->mDbFields = array();
            $this->mDbFieldKeys = array();
            $this->mAutoIncrementField = false;

            foreach ( $this->mDbColumns as $column ) {
                $parts = explode( '_', $column->Name );
                $attribute = ucfirst( $parts[ 1 ] );
                $this->mDbFields[ $column->Name ] = $attribute;
                $this->mDbFieldKeys[] = $column->Name;
                if ( $column->IsAutoIncrement ) {
                    $this->mAutoIncrementField = $column->Name;
                    // autoincrement attributes are read-only
                    $this->MakeReadOnly( $attribute );
                }
            }
            
            $this->mPrimaryKeyFields = array();
            foreach ( $this->mDbIndexes as $index ) {
                if ( $index->Type == DB_KEY_PRIMARY ) {
                    foreach ( $index->Fields as $field ) {
                        $this->mPrimaryKeyFields[] = $field->Name;
                    }
                }
            }
            if ( !count( $this->mPrimaryKeyFields ) ) {
                throw New SatoriException( 'Database table `' . $this->mDbTableAlias . '\' used for Satori class `' . get_class( $this ) . '\' does not have a primary key' );
            }

            $this->mCurrentValues = array();
            foreach ( $this->mDbFields as $fieldname => $attributename ) {
                w_assert( is_string( $fieldname ) );
                w_assert( preg_match( '#^[a-zA-Z0-9_\-]+$#', $fieldname ) );
                w_assert( is_string( $attributename ) );
                w_assert( preg_match( '#^[a-zA-Z][a-zA-Z0-9]*$#', $attributename ) );
                
                // default value
                $this->mCurrentValues[ $attributename ] = false;
            }
        }
        protected function GetFields() {
            return $this->mDbFields;
        }
        private function GrabDefaults() {
            foreach ( $this->mDbFields as $fieldname => $attributename ) {
                $this->mPreviousValues[ $attributename ] = false;
                $this->mCurrentValues[ $attributename ] = false;
            }
            $this->LoadDefaults();
            foreach ( $this->mDbFields as $fieldname => $attributename ) {
                if ( $this->mCurrentValues[ $attributename ] !== false ) {
                    $this->mDefaultValues[ $attributename ] = $value;
                    $this->mCurrentValues[ $attributename ] = false; // revert to false (doesn't invoke attribute setter)
                }
                else {
                    $this->mDefaultValues[ $attributename ] = $this->mDbColumns[ $fieldname ]->CastValueToNativeType( false );
                }
            }
        }
        protected function LoadDefaults() {
            // overload me
        }
        protected function Relations() {
            // overload me
        }
        final public function __construct( /* [ $arg1 [, $arg2 [, ... ] ] ] */ ) {
            // do not overload me!
            // possible invokations:
            // 1) Empty object:
            //     - $obj = New Object(); // creates brand new object
            // 2) Existing object by primary key:
            //     - $obj = New Object( $primaryfield1 [, $primaryfield2 [, ... ] ] );
            //       ( e.g. $obj = New Object( $objectid ) )
            // 3) Existing object by data:
            //     - $obj = New Object( $fetched_array );
            
            global $rabbit_settings;
            
            $args = func_get_args();
            
            w_assert( is_string( $this->mDbTableAlias ), 'Please specify your database table by setting member attribute mDbTableAlias for class `' . get_class( $this ) . '\'' );
            w_assert( preg_match( '#^[a-zA-Z0-9_\-]+$#', $this->mDbTableAlias ), 'Your database table alias is incorrect; make sure you have specified a valid database table alias for class `' . get_class( $this ) . '\'' );
            if ( !isset( $this->mDbName ) ) {
                if ( count( $rabbit_settings[ 'databases' ] ) ) {
                    // default database
                    $dbaliases = array_keys( $rabbit_settings[ 'databases' ] );
                    $this->mDbName = $dbaliases[ 0 ];
                }
            }
            w_assert( is_string( $this->mDbName ), 'Please specify your database by setting member attribute mDbName for class `' . get_class( $this ) . '\' to a valid database alias' );
            w_assert( $GLOBALS[ $this->mDbName ], 'The mDbName database you have specified for class `' . get_class( $this ) . '\' does not exist in your settings file' );
            $this->mDb = $GLOBALS[ $this->mDbName ];
            w_assert( $this->mDb instanceof Database, 'The database specified for class `' . get_class( $this ) . '\' is not a valid database' );
            $this->InitializeFields();
            w_assert( is_array( $this->mDbFields ), 'Database fields not properly specified for class `'. get_class( $this ) . '\'; did you incorrectly override InitializeFields()?' );
            w_assert( count( $this->mDbFields ), 'Database fields is the empty array for class `'. get_class( $this ) . '\'; does your mapped table have no columns?' );


            if ( !count( $args ) ) {
                // empty new object
                // set defaults
                $this->mExists = false;
                $this->GrabDefaults();
                $fetched_array = array();
            }
            else if ( count( $args ) == 1 && is_array( $args[ 0 ] ) ) {
                // construction by fetched array (instanciation of existing object without an SQL query)
                // (you can use this when searching to avoid issuing one query per instanciated object when
                //  instanciating multiple object -- the search system uses this)
                $fetched_array = $construct;
                $this->mExists = count( $fetched_array ) > 0;
            }
            else if ( count( $args ) == count( $this->mPrimaryKeyFields ) ) {
                $sql = 'SELECT
                            `' . implode( '`,`', $this->mDbFieldKeys ) . '`
                        FROM 
                            :' . $this->mDbTableAlias . '
                        WHERE ';
                $conditions = array();
                foreach ( $this->PrimaryKeyFields as $primary ) {
                    $conditions[] = '`' . $primary . '` = :' . $primary;
                }
                $sql .= implode( ' AND ', $conditions );
                $sql .= ' LIMIT 1';
                $query = $this->mDb->Prepare( $sql );
                $i = 0;
                foreach ( $this->PrimaryKeyFields as $primary ) {
                    $query->Bind( $primary, $args[ $i ] );
                    ++$i;
                }
                $query->BindTable( $this->mDbTableAlias );
                $res = $query->Execute();
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
                throw New Exception( 'Satori extensions must be constructed either voidly, by a primary key, or by a fetched array. `' . get_class( $this ) . '\' was instanciated unexpectedly using ' . count( $args ) . ' arguments.' );
            }
            
            foreach ( $this->mDbFields as $fieldname => $attributename ) {
                if ( isset( $fetched_array[ $fieldname ] ) ) {
                    $this->mPreviousValues[ $attributename ] = $this->mCurrentValues[ $attributename ] = $this->mDbColumns[ $fieldname ]->CastValueToNativeType( $fetched_array[ $fieldname ] );
                }
            }
        }
    }
    
    class Collection {
    }
?>
