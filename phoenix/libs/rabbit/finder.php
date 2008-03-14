<?php
    abstract class Finder {
        protected $mModel = '';
        
        protected function FindByPrototype( $prototype ) {
            w_assert( $prototype instanceof $this->mModel );
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
                :' . $prototype->DbTable->Alias . '
            WHERE
                ';
            $query = $this->mDb->Prepare( $sql );
            if ( $primary ) {
                // lookup by primary key
            }
        }
        protected function FindBySQLResource( DBResource $res ) {
            return $res->ToObjectsArray( $this->mModel );
        }
        final public function __construct() {
            
        }
    }
?>
