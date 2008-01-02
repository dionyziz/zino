<?php
    abstract class Overloadable {
        public function __set( $name, $value ) {
            // check if a custom setter is specified
            $methodname = 'Set' . $name;
            if ( method_exists( $this, $methodname ) ) {
                $success = $this->$methodname( $value ); // MAGIC!
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
                $value = $this->$methodname(); // MAGIC!
                return $value;
            }
            // else fallthru
            return null; // use null here because we want to allow custom getters to return literal boolean false
        }
    }
    
    // Active Record Base
    abstract class Satori extends Overloadable {
        protected $mDb; // database object referring to the database where the object is stored
        protected $mDbName; // name of the database we'll use for this object (defaults to your first database)
        protected $mDbTable; // database table alias this object is mapped from
        protected $mExists; // whether the current object exists in the database (this is false if a new object is created before it is saved in the database)
        private $mDbFields; // dictionary with database fields (string) => class attributes
        private $mDbFieldKeys; // list with database fields (string)
        private $mReadOnlyFields; // dictionary with class attributes => true
        private $mDbColumns; // list with DBField instances
        private $mPrimaryKeys; // list with database fields that are primary keys (string)
        protected $mPreviousValues; // stores the persistent state of this object (i.e. the stored-in-the-database version)
        protected $mCurrentValues; // stores the current state of this object (i.e. the active state that will be saved into the database upon the issue of ->Save())

        public function __set( $name, $value ) {
            global $water;
            
            if ( parent::__set( $name, $value ) === true ) {
                return;
            }

            if ( !in_array( $name, $this->mDbFields ) ) {
                $water->Warning( 'Attempting to write non-existing Satori property `' . $name . '\' on a `' . get_class( $this ) . '\' instance' );
                return;
            }
            
            if ( isset( $this->mReadOnlyFields[ $name ] ) ) {
                $water->Warning( 'Attempting to write read-only Satori attribute `' . $name . '\' on a `' . get_class( $this ) . '\' instance' );
                return;
            }
            
            $this->mCurrentValues[ ucfirst( $name ) ] = $value;
        }
        public function __get( $name ) {
            global $water;
            
            if ( !is_null( $got = parent::__get( $name ) ) ) {
                return $got;
            }

            if ( !in_array( $name, $this->mDbFields ) ) {
                $water->Warning( 'Attempting to read non-existing Satori property `' . $name . '\' on a `' . get_class( $this ) . '\' instance' );
                return;
            }
            return $this->mCurrentValues[ ucfirst( $name ) ];
        }
        public function __isset( $name ) {
            return in_array( $name, $this->mDbFields ) || in_array( $name, $this->mCurrentValues );
        }
        public function __unset( $name ) {
            global $water;
            
            $water->Warning( 'Attempting to unset Satori property on a `' . get_class( $this ) . '\' instance; Satori properties cannot be unset' );
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
            return $this->mExists;
        }
        public function Save() {
            if ( $this->Exists() ) {
                $sql = 'UPDATE
                            :' . $this->mDbTable . '
                        SET
                            ';
                $updates = array();
                $bindings = array();
                foreach ( $this->mDbFields as $fieldname => $attributename ) {
                    $varname = 'm' . ucfirst( $attributename );
                    $attributevalue = $this->$varname; // MAGIC!
                    if ( !isset( $this->mPreviousValues[ $attributename ] ) ) {
                        echo $attributename . "\n";
                    }
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
                $sql .= 'WHERE ' . $this->WhereAmI() . ' LIMIT 1;';
                $query = $this->mDb->Prepare( $sql );
                $query->BindTable( $this->mDbTable );
                foreach ( $bindings as $name => $value ) {
                    $query->Bind( $name, $value );
                }
            }
            else {
                $inserts = array();
                foreach ( $this->mDbFields as $fieldname => $attributename ) {
                    $varname = 'm' . ucfirst( $attributename );
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
            
            $query = $this->mDb->Prepare(
                'DELETE FROM
                    :' . $this->mDbTable . '
                WHERE ' . $this->WhereAmI() . ' LIMIT 1;'
            );
            $query->BindTable( $this->mDbTable );
            
            $this->mExists = false;
            return $this->mDb->Query( $sql );
        }
        protected function InitializeFields() {
            global $water;
            
            w_assert( $this->mDb instanceof Database );
            $table = $this->mDb->TableByAlias( $this->mDbTable );
            w_assert( $table instanceof DBTable );
            $this->mDbColumns = $table->Fields;
            w_assert( count( $this->mDbColumns ), 'Database table `' . $this->mDbTable . '\' used for a Satori class `' . get_class( $this ) . '\' does not have any columns' );
           
            $this->mDbFields = array();
            $this->mDbFieldKeys = array();

            foreach ( $this->mDbColumns as $column ) {
                $parts = explode( '_', $column->Name );
                $attribute = ucfirst( $parts[ 1 ] );
                $this->mDbFields[ $column->Name ] = $attribute;
                $this->mDbFieldKeys[] = $column->Name;
            }

            $this->mCurrentValues = array();
            foreach ( $this->mDbFields as $fieldname => $attributename ) {
                w_assert( is_string( $fieldname ) );
                w_assert( preg_match( '#^[a-zA-Z0-9_\-]+$#', $fieldname ) );
                w_assert( is_string( $attributename ) );
                w_assert( preg_match( '#^[a-zA-Z][a-zA-Z0-9]*$#', $attributename ) );
                
                // default value
                $this->mCurrentValues[ 'm' . ucfirst( $attributename ) ] = false;
            }

            if ( reset( $this->mDbFields ) == 'Id' ) { // TODO: use primary keys instead
                $this->MakeReadOnly( 'Id' );
            }
        }
        protected function GetFields() {
            return $this->mDbFields;
        }
        protected function LoadDefaults() {
            // overload me
        }
        protected function Relations() {
            // overload me
        }
        final public function __construct( $construct = false ) {
            // do not overload me!
            global $water;
            global $rabbit_settings;
            
            w_assert( is_string( $this->mDbTable ), 'Please specify your database table by setting member attribute mDbTable for class `' . get_class( $this ) . '\'' );
            w_assert( preg_match( '#^[a-zA-Z0-9_\-]+$#', $this->mDbTable ), 'Your database table alias is incorrect; make sure you have specified a valid database table alias for class `' . get_class( $this ) . '\'' );
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

            if ( $construct === false ) {
                // empty new object
                // set defaults
                $this->mExists = false;
                $this->LoadDefaults();
                $fetched_array = array();
            }
            else if ( ValidId( $construct ) ) { // TODO: use primary key instead
                $this->mId = $construct; // overload constructor in case of ValidId() if you don't use Ids
                $query = $this->mDb->Prepare(
                    'SELECT
                        ' . implode( ',', $this->mDbFieldKeys ) . '
                    FROM 
                        :' . $this->mDbTable . '
                    WHERE ' . $this->WhereAmI() . ' LIMIT 1'
                );
                $query->BindTable( $this->mDbTable );
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
                $this->mExists = count( $fetched_array ) > 0;
            }
            
            foreach ( $this->mDbFields as $fieldname => $attributename ) {
                if ( isset( $fetched_array[ $fieldname ] ) ) {
                    $varname = 'm' . ucfirst( $attributename );
                    $this->$varname = $fetched_array[ $fieldname ]; // MAGIC!
                    $this->mPreviousValues[ $attributename ] = $fetched_array[ $fieldname ];
                }
                else {
                    $varname = 'm' . ucfirst( $attributename );
                    if ( empty( $this->$varname ) ) { // MAGIC!
                        $this->$varname = false; // MAGIC!
                    }
                    $this->mPreviousValues[ $attributename ] = $this->$varname; // MAGIC!
                }
            }
        }
    }
?>
