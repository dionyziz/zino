<?php
    abstract class Finder {
        protected $mModel = '';
        protected $mDbTableAlias;
        protected $mDbIndexes;
        protected $mDb;
        
        protected function FindByPrototype( $prototype, $offset = 0, $limit = 25 ) {
            w_assert( $prototype instanceof $this->mModel );
            w_assert( is_int( $offset ) );
            w_assert( is_int( $limit ) );
            
            $mods = $prototype->FetchPrototypeChanges();
            if ( !count( $mods ) ) {
                return array();
            }
            // check if this lookup will yield to a unique result
            // this type of lookups will either return a single record or none
            $unique = false;
            /*
            echo $this->mModel;
            ?> <?php
            foreach ( $this->mDbIndexes as $index ) {
                echo $index->Type;
                ?> <?php
            }
            die();
            */
            
            foreach ( $this->mDbIndexes as $index ) {
                switch ( $index->Type ) {
                    case DB_KEY_UNIQUE:
                    case DB_KEY_PRIMARY:
                        $unique = true;
                        foreach ( $index->Fields as $field ) {
                            if ( !isset( $mods[ $field->Name ] ) ) {
                                $unique = false;
                                break;
                            }
                        }
                    default:
                }
                if ( $unique ) {
                    // if this lookup is a subseteq of at least one unique/primary key, this is sufficient
                    break;
                }
            }
            
            $sql = 'SELECT
                        *
                    FROM
                        :' . $prototype->DbTable->Alias;
            $where = array();
            foreach ( $mods as $column => $value ) {
                $where[] = '`' . $column . '` = ' . ':_' . $column;
            }
            if ( count( $where ) ) {
                $sql .= ' WHERE ' . implode( ' AND ', $where );
            }
            $sql .= ' LIMIT :__offset, :__limit';
            $query = $this->mDb->Prepare( $sql );
            $query->BindTable( $this->mDbTableAlias );
            foreach ( $mods as $column => $value ) {
                $query->Bind( '_' . $column, $value );
            }
            $query->Bind( '__offset', $offset );
            $query->Bind( '__limit', $limit );
            $res = $query->Execute();
            if ( $unique ) {
                // lookup by primary key
                return New $this->mModel( $res->FetchArray() );
            }
            return $this->FindBySQLResource( $res );
        }
        protected function FindBySQLResource( DBResource $res ) {
            return $res->ToObjectsArray( $this->mModel );
        }
        final public function __construct() {
            $prototype = New $this->mModel();
            $this->mDb = $prototype->Db; // TODO: cache this across all finder instances?
            $this->mDbTableAlias = $prototype->DbTable->Alias;
            $this->mDbIndexes = $prototype->DbTable->Indexes;
        }
    }
?>
