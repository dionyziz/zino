<?php
    /*
        Developer: Dionyziz
    */
    
    global $libs;
    
    $libs->Load( 'rabbit/activerecord/finder' );
    $libs->Load( 'rabbit/activerecord/collection' );
    
    class SatoriException extends Exception {
    }
    
    abstract class Relation {
        protected $mQueryModel;
        protected $mRetrieved = 0; // 0 designates not retrieved yet; false may be used to represent other things (such as not found)
        
        public abstract function __construct();
        protected abstract function MakeObj();
        protected abstract function Equals( Relation $target );
        protected abstract function Modified();
        public function Rebuild() {
            if ( ( !is_object( $this->mRetrieved ) && $this->mRetrieved == 0 ) ) {
                // it hasn't even been BUILD, so no need to REbuild
                return;
            }
            /* else */
            if ( $this->Modified() ) {
                $this->mRetrieved = $this->MakeObj();
            }
        }
        public function Retrieve() {
            if ( $this->mRetrieved === 0 ) {
                $this->mRetrieved = $this->MakeObj();
            }
            return $this->mRetrieved;
        }
    }
    
    class RelationHasOne extends Relation {
        protected $mForeignKey;
        protected $mAttribute2DbField;
        protected $mTargetModelClass;
        protected $mCurrentArgs;
        
        public function IsSameAs( $targetModelClass, $foreignKey) {
            return 
                   $this->mTargetModelClass == $targetModelClass 
                && $this->mForeignKey == $foreignKey;
        }
        public function Equals( Relation $target ) {
            return $target->IsSameAs( $this->mTargetModelClass, $this->mForeignKey ); 
        }
        public function __construct( $queryModel, $targetModelClass, $foreignKey ) {
            if ( !is_array( $foreignKey ) ) {
                $foreignKey = array( $foreignKey );
            }
            $this->mQueryModel = $queryModel;
            $this->mTargetModelClass = $targetModelClass;
            w_assert( class_exists( $this->mTargetModelClass ), 'Model class `' . $this->mTargetModelClass . '\' used in HasOne relation of ' . get_class( $this->mQueryModel ) . ' is not defined' );
            $this->mForeignKey = $foreignKey;
            $this->mAttribute2DbField = $queryModel->Attribute2DbField;
            $this->mCurrentArgs = false;
        }
        protected function Args() {
            if ( $this->mCurrentArgs === false ) {
                $this->mCurrentArgs = $this->RetrieveCurrentArgs();
            }
            return $this->mCurrentArgs;
        }
        protected function RetrieveCurrentArgs() {
            $args = array();
            foreach ( $this->mForeignKey as $attribute ) {
                if ( !isset( $this->mAttribute2DbField[ $attribute ] ) ) {
                    throw New SatoriException( 'Foreign key `' . $attribute . '\' of HasOne relation of ' . get_class( $this->mQueryModel ) . ' is not an existing attribute name' );
                }
                $args[] = $this->mQueryModel->$attribute;
            }
            return $args;
        }
        protected function Modified() {
            // check if $args (current arguments) and $this->mCurrentArgs (stored old arguments) are one and the same
            $args = $this->RetrieveCurrentArgs();
            w_assert( count( $args ) == count( $this->mCurrentArgs ) );
            $i = 0;
            $change = false; // assume nothing changed, we might disprove this later
            $modified = false; // assume no serious modification took place
            foreach ( $args as $arg ) {
                if ( $arg != $this->mCurrentArgs[ $i ] ) {
                    // something changed...
                    $change = true;
                    // check if change is significant
                    if ( $this->mQueryModel->IsSignificantAttribute( $this->mForeignKey[ $i ] ) ) {
                        // some modification took place that we need to take into account
                        $modified = true;
                    }
                }
                ++$i;
            }

            if ( $change ) { // if something changed, update current args
                $this->mCurrentArgs = $args;
                if ( !$modified ) { // no significant change, but we still need to update our primary key value changes
                    $this->mRetrieved->DefinePrimaryKeyAttributes( $args );
                }
            }

            return $modified;
        }
        protected function MakeObj() {
            // instantiate $className with a variable number of arguments (the number of columns in the primary key can vary)
            $class = New ReflectionClass( $this->mTargetModelClass );
            $args = $this->Args();
            
            // create object instance to referenced object
            $target = $class->newInstanceArgs( $args );
            if ( !$target->Exists() ) { // no such object
                // create empty new object instance
                $target = $class->newInstanceArgs( array() );
                // define primary keys
                $target->DefinePrimaryKeyAttributes( $args );
            }
            return $target;
        }
        public function CopyFrom( $obj ) {
            w_assert( is_object( $obj ), 'CopyFrom on HasOne relation of ' . get_class( $this->mQueryModel ) . ' must be an object'  );
            w_assert( $obj instanceof $this->mTargetModelClass, 'CopyFrom on HasOne relation of ' . get_class( $this->mQueryModel ) . ' must be a `' . $this->mTargetModelClass . ' instance, but ' . get_class( $obj ) . ' given'  );

            $this->mRetrieved = $obj;
        }
    }
    
    class RelationHasMany extends Relation {
        protected $mFinderClass;
        protected $mFinderMethod;
        protected $mForeignKey;

        public function IsSameAs( $finderClass, $finderMethod, $foreignKey ) {
            $equals = $finderClass == $this->mFinderClass && $finderMethod == $this->mFinderMethod;
            if ( !$equals ) {
                return false;
            }
            if ( is_string( $foreignKey ) ) {
                if ( is_string( $this->mForeignKey ) ) {
                    return $foreignKey == $this->mForeignKey; // both are strings
                }
                return false; // remote is string, local is object
            }
            if ( is_string( $this->mForeignKey ) ) {
                return false; // local is string, remote is object
            }
            return true; // both are objects, assume equality and don't bother checking (too slow)
        }
        public function Equals( Relation $target ) {
            return $target->IsSameAs( $this->mFinderClass, $this->mFinderMethod, $this->mForeignKey );
        }
        public function __construct( $queryModel, $finderClass, $finderMethod, $foreignKey ) {
            if ( !class_exists( $finderClass ) ) {
                throw New SatoriException( 'Finder class `' . $finderClass . '\' used in HasMany relation of `' . get_class( $this ) . '\' specified for HasMany relation does not exist' );
            }
            $this->mQueryModel = $queryModel;
            $this->mFinderClass = $finderClass;
            $this->mFinderMethod = $finderMethod;
            $this->mForeignKey = $foreignKey;
        }
        public function Modified() {
            return false; // too expensive to detect automatically
        }
        public function MakeObj() {
            $finder = New $this->mFinderClass(); // MAGIC!
            if ( !is_subclass_of( $finder, 'Finder' ) ) {
                throw New SatoriException( 'Finder class `' . $this->mFinderClass . '\' used in HasMany relation of `' . get_class( $this ) . '\' does not extend the "Finder" base' );
            }

            $methodName = $this->mFinderMethod;
            if ( !method_exists( $finder, $methodName ) ) {
                throw New SatoriException( 'Method `' . $methodName . '\' of finder class `' . $this->mFinderClass . '\' used for HasMany relation of `' . get_class( $this->mQueryModel ) . '\' is not defined' );
            }
            if ( is_string( $this->mForeignKey ) ) { // passing of an attribute name -- the "normal" way to do it, as it allows detecting changes
                $key = ucfirst( $this->mForeignKey );
                $value = $this->mQueryModel->$key;
            }
            else if ( is_object( $this->mForeignKey ) ) { // direct passing of value (allowed for convenience)
                $value = $this->mForeignKey;
            }
            return $finder->$methodName( $value ); // MAGIC!
        }
    }
    
    function Satori_GetPrototypeInstance( $classname ) {
        // just a faster way of getting a prototype satori extension instance than instantiating every time
        // this instance should only be used for information retrieval and should not be modified or made persistent

        // TODO: Move this into a static method within class Satori once late static binding is available in PHP 5.3
        static $instances;

        if ( !isset( $instances[ $classname ] ) ) {
            $instances[ $classname ] = New $classname();
        }
        return $instances[ $classname ];
    }

    // Active Record Base
    abstract class Satori {
        protected $mDb; // database object referring to the database where the object is stored
        protected $mDbName; // name of the database we'll use for this object (defaults to your first database)
        protected $mDbTableAlias; // database table alias this object is mapped from
        protected $mDbTable; // DBTable instance for the database table this object is mapped from
        protected $mExists; // whether the current object exists in the database (this is false if a new object is created before it is saved in the database)
        private $mDbFields; // dictionary with full database field name (string) => class attribute name
        private $mAttribute2DbField; // flip of the above
        private $mDbFieldKeys; // list with database fields (string)
        private $mReadOnlyFields; // dictionary with class attributes => true
        private $mDbColumns; // dictionary with field name (string) => DBField instance
        private $mDbIndexes; // list with DBIndex instances
        private $mPrimaryKeyFields; // list with database fields that are primary keys (array of string)
        protected $mPreviousValues; // stores the persistent state of this object (i.e. the stored-in-the-database version)
        protected $mCurrentValues; // stores the current state of this object (i.e. the active state that will be saved into the database upon the issue of ->Save())
        protected $mAutoIncrementField; // string name of the database field that is autoincrement, or false if there is no autoincrement field
        protected $mDefaultValues; // dictionary with attribute name (string) => default value, to be used if value of empty object remains at 'false'
        protected $mRelations;
        private $mOldRelations; // temporary holder of old relations while they are being redefined
        protected $mReadOnlyModified; // boolean; whether there has been an attempt to modify a read-only attribute (allowed providing the object is non-persistent and never made persistent)
        protected $mAllowRelationDefinition;
        protected $mInsertIgnore = false;
       
        public function __get( $key ) {
            switch ( $key ) {
                case 'Attribute2DbField':
                case 'Db':
                case 'DbTable':
                case 'DbFields':
                case 'PrimaryKeyFields':
                    $attribute = 'm' . $key;
                    return $this->$attribute;
            }
            
            if ( isset( $this->mRelations[ $key ] ) ) {
                return $this->mRelations[ $key ]->Retrieve();
            }
            
            $key = ucfirst( $key );
            if ( !in_array( $key, $this->mDbFields ) ) {
                throw New SatoriException( 'Attempting to read non-existing Satori property `' . $key . '\' on a `' . get_class( $this ) . '\' instance' );
            }
            return $this->mCurrentValues[ $key ];
        }
        public function __set( $name, $value ) {
            if ( $this->mAllowRelationDefinition && $value instanceof Relation ) {
                if ( isset( $this->mOldRelations[ $name ] ) ) {
                    if ( $this->mOldRelations[ $name ]->Equals( $value ) ) {
                        $this->mRelations[ $name ] = $this->mOldRelations[ $name ];
                        return; // no need to update it
                    }
                }
                $this->mRelations[ $name ] = $value;
                return;
            }

            $name = ucfirst( $name );
            if ( !in_array( $name, $this->mDbFields ) ) {
                throw New SatoriException( 'Attempting to write non-existing Satori property `' . $name . '\' on a `' . get_class( $this ) . '\' instance' );
            }
            
            if ( isset( $this->mReadOnlyFields[ $name ] ) ) {
                if ( !$this->Exists() ) {
                    $this->mReadOnlyModified = true;
                }
                else {
                    throw New SatoriException( 'Attempting to write read-only Satori attribute `' . $name . '\' on a `' . get_class( $this ) . '\' instance' );
                }
            }
            
            $this->mCurrentValues[ $name ] = $value;
        }
        protected function Relations() {
            // override me
        }
        public function IsSignificantAttribute( $attribute ) {
            // does a change in the field named $fieldname that a relation relies upon require a relation rebuild?
            // not if the value is generated within this very instance in a way that the related classes cannot access directly,
            // such as an autoincrement value

            if ( $this->mAutoIncrementField == $this->mAttribute2DbField[ $attribute ] ) {
                return false;
            }
            return true;
        }
        protected function HasOne( $className, $foreignKey ) {
            if ( !$this->mAllowRelationDefinition ) {
                throw New SatoriException( 'HasOne relations must be defined in the Relations() function of `' . get_class( $this ) . '\'' );
            }
            return New RelationHasOne( $this, $className, $foreignKey );
        }
        protected function HasMany( $finderName, $methodName, $foreignKey ) {
            if ( !$this->mAllowRelationDefinition ) {
                throw New SatoriException( 'HasOne relations must be defined in the Relations() function of `' . get_class( $this ) . '\'' );
            }
            return New RelationHasMany( $this, $finderName, $methodName, $foreignKey );
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
        protected function DefineRelations() {
            $this->mOldRelations = $this->mRelations;
            $this->mRelations = array();

            $this->mAllowRelationDefinition = true;
            $this->mRelations = array();
            $this->Relations();
            $this->mAllowRelationDefinition = false;
        }
        final public function Save() {
            if ( $this->mReadOnlyModified ) {
                throw New SatoriException( 'This object has modified read-only attributes; it cannot be made persistent' );
            }
            if ( $this->Exists() ) {
                if ( $this->OnBeforeUpdate() === false ) {
                    return false;
                }
                $sql = 'UPDATE
                            :' . $this->mDbTableAlias . '
                        SET
                            ';
                $updates = array();
                $bindings = array();
                $updatedAttributes = array();
                $previousValues = array();
                foreach ( $this->mDbFields as $fieldname => $attributename ) {
                    $attributevalue = $this->mCurrentValues[ $attributename ];
                    if ( $this->mPreviousValues[ $attributename ] != $attributevalue ) {
                        $updates[] = "`$fieldname` = :$fieldname";
                        $bindings[ $fieldname ] = $attributevalue;
                        $updatedAttributes[ $attributename ] = true;
                        $previousValues[ $attributename ] = $this->mPreviousValues[ $attributename ];
                        $this->mPreviousValues[ $attributename ] = $attributevalue;
                    }
                }
                if ( !count( $updates ) ) {
                    $this->OnUpdate( array(), array() );

                    // nothing to update
                    return true;
                }
                
                $sql .= implode( ', ', $updates );
                $sql .= ' WHERE ';
                $conditions = array();
                foreach ( $this->mPrimaryKeyFields as $primarykeyfield ) {
                    $conditions[] = '`' . $primarykeyfield . '` = :_' . $primarykeyfield;
                }
                $sql .= implode( ' AND ', $conditions );
                $sql .= ' LIMIT 1;';
                $query = $this->mDb->Prepare( $sql );
                $query->BindTable( $this->mDbTableAlias );
                foreach ( $this->mPrimaryKeyFields as $primarykeyfield ) {
                    // search using the previous values of the primary key (if its value changed)
                    if ( isset( $previousValues[ $this->mDbFields[ $primarykeyfield ] ] ) ) {
                        $value = $previousValues[ $this->mDbFields[ $primarykeyfield ] ];
                    }
                    else {
                        $value = $this->mCurrentValues[ $this->mDbFields[ $primarykeyfield ] ];
                    }
                    $query->Bind( '_' . $primarykeyfield, $value );
                }
                foreach ( $bindings as $name => $value ) {
                    $query->Bind( $name, $value );
                }
                $change = $query->Execute();
                $this->DefineRelations();
                foreach ( $this->mRelations as $relation ) {
                    $relation->Rebuild();
                }
                $this->OnUpdate( $updatedAttributes, $previousValues );
                return $change;
            }
            else {
                $this->ResolveDefaultValues();
                if ( $this->OnBeforeCreate() === false ) {
                    return;
                }
                $inserts = array();
                foreach ( $this->mDbFields as $fieldname => $attributename ) {
                    $inserts[ $fieldname ] = $this->mCurrentValues[ $attributename ];
                    $this->mPreviousValues[ $attributename ] = $this->mCurrentValues[ $attributename ];
                }
                $change = $this->mDbTable->InsertInto( $inserts, $this->mInsertIgnore );
                if ( $change->Impact() ) {
                    if ( $this->mAutoIncrementField !== false ) {
                        $field = $this->mDbFields[ $this->mAutoIncrementField ];
                        $this->mPreviousValues[ $field ] = $this->mCurrentValues[ $field ] = $change->InsertId();
                    }
                }
                $this->mExists = true;
                $this->DefineRelations();
                foreach ( $this->mRelations as $attribute => $relation ) {
                    $relation->Rebuild();
                }
                $this->OnCreate();
                return $change;
            }
        }
        protected function OnUpdate() {
            // override me
        }
        protected function OnCreate() {
            // override me
        }
        protected function OnDelete() {
            // override me
        }
        protected function OnBeforeCreate() {
            // override me
        }
        protected function OnBeforeUpdate() {
            // override me
        }
        protected function OnBeforeDelete() {
            // override me
        }
        final public function Delete() {
            if ( !$this->Exists() ) {
                throw New SatoriException( 'Cannot delete non-existing Satori object' );
            }
            
            if ( $this->OnBeforeDelete() === false ) {
                return;
            }

            $sql = 'DELETE FROM
                        :' . $this->mDbTableAlias . '
                    WHERE ';
            $conditions = array();
            foreach ( $this->mPrimaryKeyFields as $primary ) {
                $conditions[] = '`' . $primary . '` = :' . $primary;
            }
            $sql .= implode( ' AND ', $conditions );
            $sql .= ' LIMIT 1';
            $query = $this->mDb->Prepare( $sql );
            $i = 0;
            foreach ( $this->mPrimaryKeyFields as $primary ) {
                // delete using the values of mPreviousValues in the primary key
                $query->Bind( $primary, $this->mPreviousValues[ $this->mDbFields[ $primary ] ] );
                ++$i;
            }
            $query->BindTable( $this->mDbTableAlias );
            
            $this->mExists = false;

            $res = $query->Execute();

            $this->OnDelete();

            return $res;
        }
        protected function InitializeFields() {
            global $rabbit_settings;

            w_assert( $this->mDb instanceof Database, 'Database not specified or invalid for Satori class `' . get_class( $this ). '\'' );
            
            $this->mDbTable = $this->mDb->TableByAlias( $this->mDbTableAlias );
            
            w_assert( $this->mDbTable instanceof DBTable, 'Database table not specified, invalid, or database table alias non-existing for Satori class `' . get_class( $this ) . '\'' );
            
            $this->mDbColumns = $this->mDbTable->Fields;
            if ( !count( $this->mDbColumns ) ) {
                throw New SatoriException( 'Database table `' . $this->mDbTableAlias . '\' used for Satori class `' . get_class( $this ) . '\' does not have any columns' );
            }
            $this->mDbIndexes = $this->mDbTable->Indexes;
            if ( !count( $this->mDbIndexes ) ) {
                throw New SatoriException( 'Database table `' . $this->mDbTableAlias . '\' used for Satori class `' . get_class( $this ) . '\' does not have any keys (primary key required)' );
            }
            
            $this->mDbFields = array(); // TODO: cache
            $this->mDbFieldKeys = array_keys( $this->mDbColumns );
            $this->mAutoIncrementField = false;
            
            foreach ( $this->mDbFieldKeys as $columnname ) {
                $parts = explode( '_', $columnname );
                $attribute = ucfirst( $parts[ 1 ] );
                $this->mDbFields[ $columnname ] = $attribute;
            }
            $this->mAttribute2DbField = array_flip( $this->mDbFields ); // TODO: cache this across all instances of the same Satori object
            
            $this->mPrimaryKeyFields = array(); // TODO: cache
            foreach ( $this->mDbIndexes as $index ) {
                if ( $index->Type == DB_KEY_PRIMARY ) {
                    if ( count( $index->Fields ) == 1 ) {
                        if ( $index->Fields[ 0 ]->IsAutoIncrement ) {
                            $this->mAutoIncrementField = $index->Fields[ 0 ]->Name;
                            $parts = explode( '_', $this->mAutoIncrementField );
                            // autoincrement attributes are read-only
                            $this->MakeReadOnly( ucfirst( $parts[ 1 ] ) );
                        }
                    }
                    foreach ( $index->Fields as $field ) {
                        $this->mPrimaryKeyFields[] = $field->Name;
                    }
                    break;
                }
            }
            if ( !count( $this->mPrimaryKeyFields ) ) {
                throw New SatoriException( 'Database table `' . $this->mDbTableAlias . '\' used for Satori class `' . get_class( $this ) . '\' does not have a primary key' );
            }
            
            // default values
            $this->mCurrentValues = array_combine( $this->mDbFields, array_fill( 0, count( $this->mDbFields ), false ) ); // TODO: cache
            
            if ( !$rabbit_settings[ 'production' ] ) {
                foreach ( $this->mDbFields as $fieldname => $attributename ) {
                    if ( !$rabbit_settings[ 'production' ] ) {
                        w_assert( is_string( $fieldname ) );
                        w_assert( preg_match( '#^[a-zA-Z0-9_\-]+$#', $fieldname ) );
                        w_assert( is_string( $attributename ) );
                        w_assert( preg_match( '#^[a-zA-Z][a-zA-Z0-9]*$#', $attributename ) );
                    } 
                }
            }
        }
        public function FetchPrototypeChanges() {
            $mods = array();
            foreach ( $this->mDbFields as $fieldname => $attributename ) {
                if ( $this->mCurrentValues[ $attributename ] !== false ) {
                    $mods[ $fieldname ] = $this->mCurrentValues[ $attributename ];
                }
            }
            return $mods;
        }
        private function GrabDefaults() {
            // fills in the $this->mDefaultValues based on:
            // 1) LoadDefaults(), if it contains some defaulting rule
            // 2) The default value of each data type (e.g. 0 for db integers)
            //
            // sets current/previous values to boolean false
            foreach ( $this->mDbFields as $fieldname => $attributename ) {
                $this->mPreviousValues[ $attributename ] = false;
                $this->mCurrentValues[ $attributename ] = false;
            }
            $this->LoadDefaults();
            foreach ( $this->mDbFields as $fieldname => $attributename ) {
                if ( $this->mCurrentValues[ $attributename ] !== false ) {
                    $this->mDefaultValues[ $attributename ] = $this->mCurrentValues[ $attributename ];
                    $this->mCurrentValues[ $attributename ] = false; // revert to false (doesn't invoke attribute setter)
                }
                else {
                    $this->mDefaultValues[ $attributename ] = $this->mDbColumns[ $fieldname ]->CastValueToNativeType( false );
                }
            }
        }
        private function ResolveDefaultValues() {
            // sets the values of the domain attributes that remain unresolved (as "false")
            // to the default ones they should be
            foreach ( $this->mDbFields as $fieldname => $attributename ) {
                if ( $this->mCurrentValues[ $attributename ] === false ) {
                    $this->mCurrentValues[ $attributename ] = $this->mDefaultValues[ $attributename ];
                }
            }
        }
        protected function LoadDefaults() {
            // overload me
        }
        protected function OnConstruct( /* [ $arg1 [, $arg2, [, ... ] ] ] */ ) {
            // overload me
        }
        final public function DefinePrimaryKeyAttributes( $values ) {
            if ( count( $values ) != count( $this->mPrimaryKeyFields ) ) {
                throw New SatoriException( 'DefinePrimaryKeyAttributes called with an incorrect number of arguments' );
            }
            reset( $values );
            foreach ( $this->mPrimaryKeyFields as $primary ) {
                if ( $this->mAutoIncrementField !== false && $primary == $this->mAutoIncrementField ) {
                    next( $values );
                    continue;
                }
                $this->mCurrentValues[ $this->mDbFields[ $primary ] ] = current( $values );
                next( $values );
            }
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
                $fetched_array = $args[ 0 ];
                $this->mExists = count( $fetched_array ) > 0;
            }
            else if ( count( $args ) == count( $this->mPrimaryKeyFields ) ) {
                $sql = 'SELECT
                            `' . implode( '`,`', $this->mDbFieldKeys ) . '`
                        FROM 
                            :' . $this->mDbTableAlias . '
                        WHERE ';
                $conditions = array();
                $i = 0;
                $invalid = false;
                foreach ( $this->mPrimaryKeyFields as $primary ) {
                    if ( $this->mAutoIncrementField == $primary ) {
                        if ( $args[ $i ] == 0 ) { // autoincrement field is 0, object can't exist
                            // (this situation can be created by manually inserting a row with autoincrement set to 0, but it's a rare case and a good optimization to care about)
                            $invalid = true;
                            break;
                        }
                    }
                    $conditions[] = '`' . $primary . '` = :' . $primary;
                    ++$i;
                }
                if ( !$invalid ) {
                    $sql .= implode( ' AND ', $conditions );
                    $sql .= ' LIMIT 1';
                    $query = $this->mDb->Prepare( $sql );
                    $i = 0;
                    foreach ( $this->mPrimaryKeyFields as $primary ) {
                        $query->Bind( $primary, $args[ $i ] );
                        ++$i;
                    }
                    $query->BindTable( $this->mDbTableAlias );
                    $res = $query->Execute();
                }
                if ( $invalid || $res->NumRows() != 1 ) {
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
            
            $this->DefineRelations();
            call_user_func_array( array( $this, 'OnConstruct' ), $args );
        }
        public function __toString() {
            if ( $this->Exists() ) {
                $str = 'Persistent ';
            }
            else {
                $str = 'Non-persistent ';
            }
            $str .= 'Satori object ' . get_class( $this );
            $updates = array();
            foreach ( $this->mDbFields as $fieldname => $attributename ) {
                if ( $this->mPreviousValues[ $attributename ] != $this->mCurrentValues[ $attributename ] ) {
                    $str .= ' (modified)';
                    break;
                }
            }
            return $str;
        }
    }
?>
