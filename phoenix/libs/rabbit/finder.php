<?php
    abstract class Finder {
        protected $mModel = '';
        protected $mDb;
        
        protected function FindByPrototype( $prototype, $offset = 0, $limit = 25 ) {
            w_assert( $prototype instanceof $this->mModel );
            w_assert( is_int( $offset ) );
            w_assert( is_int( $limit ) );
            
            $mods = $prototype->FetchPrototypeChanges();
            if ( !count( $mods ) ) {
                return array();
            }
            // check if this is a primary key lookup
            $keys = $prototype->PrimaryKeyFields;
            $primary = false;
            if ( count( $keys ) == count( $mods ) ) {
                $primary = true;
                foreach ( $keys as $field ) {
                    if ( !isset( $mods[ $field ] ) ) {
                        $primary = false;
                        break;
                    }
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
            $query->BindTable( $this->mModel->DbTable->Alias );
            foreach ( $mods as $column => $value ) {
                $query->Bind( '_' . $column, $value );
            }
            $query->Bind( '__offset', $offset );
            $query->Bind( '__limit', $limit );
            $res = $query->Execute();
            if ( $primary ) {
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
            $this->mDb = $prototype->mDb; // TODO: cache this across all finder instances?
        }
    }
?>
