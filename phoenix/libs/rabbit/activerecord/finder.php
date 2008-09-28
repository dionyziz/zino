<?php
    abstract class Finder {
        protected $mModel = '';
        protected $mDbTableAlias;
        protected $mDbIndexes;
        protected $mAttribute2DbField;
        protected $mDb;
        
        protected function FindByPrototype( $prototype, $offset = 0, $limit = 25, $order = false, $calcfoundrows = false ) {
            w_assert( $prototype instanceof $this->mModel, 'Prototype specified in FindByPrototype call in finder `' . get_class( $this ) . '\' must be an instance of `' . $this->mModel . '\'' );
            w_assert( is_int( $offset ), 'Offset must be an integer in FindByPrototype call in finder `' . get_class( $this ) . '\' ' . gettype( $offset ) . ' given' );
            w_assert( is_int( $limit ), 'Limit must be an integer in FindByPrototype call in finder `' . get_class( $this ) . '\', ' . gettype( $limit ) . ' given' );
            
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
            
            $sql = 'SELECT ';
            if ( !$unique && $calcfoundrows ) {
                $sql .= 'SQL_CALC_FOUND_ROWS'; 
            }
            $sql .= '
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
            $totalcount = false;
            if ( $calcfoundrows ) {
                $totalcount = ( int )array_shift(
                    $this->mDb->Prepare(
                        'SELECT FOUND_ROWS();'
                    )->Execute()->FetchArray()
                );
            }
            return $this->FindBySQLResource( $res, $totalcount );
        }
        protected function FindBySQLResource( DBResource $res, $totalcount = false ) {
            if ( $totalcount !== false ) {
                return New Collection( $res->ToObjectsArray( $this->mModel ), $totalcount );
            }
            return $res->ToObjectsArray( $this->mModel );
        }
        protected function Count() {
            $query = $this->mDb->Prepare(
                "SELECT
                    COUNT( * ) AS numrows
                FROM
                    :" . $this->mDbTableAlias . ";"
            );
            $query->BindTable( $this->mDbTableAlias );
            $res = $query->Execute();
            $row = $res->FetchArray();
            return ( int )$row[ 'numrows' ];
        }
        final public function __construct() {
            global $rabbit_settings;

            w_assert( !empty( $this->mModel ), 'mModel not defined for finder `' . get_class( $this ) . '\'' );
            $prototype = Satori_GetPrototypeInstance( $this->mModel );
            w_assert( is_subclass_of( $prototype, 'Satori' ), 'mModel defined for finder `' . get_class( $this ) . '\' must be a class extending Satori' );
            $this->mDb = $prototype->Db;
            w_assert( is_object( $this->mDb ) );
            $this->mDbTableAlias = $prototype->DbTable->Alias;
            $this->mDbIndexes = $prototype->DbTable->Indexes;
            $this->mAttribute2DbField = array_flip( $prototype->DbFields );
        }
    }
?>
