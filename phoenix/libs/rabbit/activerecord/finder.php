<?php
    abstract class Finder {
        protected $mModel = '';
        protected $mDbTableAlias;
        protected $mDbIndexes;
        protected $mAttribute2DbField;
        protected $mDb;
        
        protected function FindByPrototype( $prototype, $offset = 0, $limit = 25, $order = false ) {
            w_assert( $prototype instanceof $this->mModel, 'Prototype specified in FindByPrototype call in finder `' . get_class( $this ) . '\' must be an instance of `' . $this->mModel . '\'' );
            w_assert( is_int( $offset ) );
            w_assert( is_int( $limit ) );
            
            $mods = $prototype->FetchPrototypeChanges();
            
            // check if this lookup will yield to a unique result
            // this type of lookups will either return a single record or none
            $unique = false;
            
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
            if ( $order !== false ) {
                if ( is_string( $order ) ) {
                    $column = $order;
                    $sort = 'ASC';
                }
                else if ( is_array( $order ) ) {
                    if ( count( $order ) != 2 ) {
                        throw New SatoriException( 'Order clause specified for prototype finder `' . get_class( $this ) . '\' is invalid: It must be a string or a 2-item array; ' . count( $order ) . '-item array given.' );
                    }
                    if ( !isset( $order[ 0 ] ) ) {
                        throw New SatoriException( 'Order clause specified for prototype finder `' . get_class( $this ) . '\' is invalid: The 2-item array must be numerically indexed.' );
                    }
                    if ( !isset( $order[ 1 ] ) ) {
                        throw New SatoriException( 'Order clause specified for prototype finder `' . get_class( $this ) . '\' is invalid: The 2-item array must be numerically indexed.' );
                    }
                    $column = $order[ 0 ];
                    $sort = $order[ 1 ];
                }
                if ( !preg_match( '#^[a-zA-Z_\-0-9]+$#', $column ) ) {
                    throw New SatoriException( 'Order clause specified for prototype finder `' . get_class( $this ) . '\' is invalid: Attribute field must contain a legal attribute name, "' . $column . '" given.' );
                }
                if ( $sort != 'ASC' && $sort != 'DESC' ) {
                    throw New SatoriException( 'Order clause specified for prototype finder `' . get_class( $this ) . '\' is invalid: Sort field must be ASC or DESC, "' . $sort . '" given.' );
                }
                if ( !isset( $this->mAttribute2DbField[ $column ] ) ) {
                    throw New SatoriException( 'Order clause specified for prototype finder `' . get_class( $this ) . '\' is invalid: Attribute field "' . $column . '" does not exist in corresponding model as a domain-level attribute.' );
                }
                $sql .= ' ORDER BY ' . $this->mAttribute2DbField[ $column ] . ' ' . $sort;
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
                if ( $res->Results() ) {
                    return New $this->mModel( $res->FetchArray() );
                }
                return false;
            }
            return $this->FindBySQLResource( $res );
        }
        protected function FindBySQLResource( DBResource $res ) {
            return $res->ToObjectsArray( $this->mModel );
        }
        final public function __construct() {
            if ( empty( $this->mModel ) ) {
                throw New SatoriException( 'mModel not defined for finder `' . get_class( $this ) . '\'' );
            }
            $prototype = New $this->mModel();
            if ( !is_subclass_of( $prototype, 'Satori' ) ) {
                throw New SatoriException( 'mModel defined for finder `' . get_class( $this ) . '\' must be a class extending Satori' );
            }
            $this->mDb = $prototype->Db; // TODO: cache this across all finder instances?
            $this->mDbTableAlias = $prototype->DbTable->Alias;
            $this->mDbIndexes = $prototype->DbTable->Indexes;
            $this->mAttribute2DbField = array_flip( $prototype->DbFields );
        }
    }
?>
