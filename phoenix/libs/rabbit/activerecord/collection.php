<?php
    class Collection extends ArrayIterator {
        protected $mTotalCount;

        public function PreloadRelation( $relationAttribute, $className = false, $foreignKey = false, $finderClass = false, $finderMethod = false ) {
            /* Defaults */
            if ( $className === false ) {
                $className = $relationAttribute;
            }
            if ( $foreignKey === false ) {
                $foreignKey = ucfirst( $relationAttribute . 'id' );
            }
            if ( $finderClass === false ) {
                $finderClass = $className . 'Finder';
            }
            if ( $finderMethod === false ) {
                $finderMethod = 'FindByIds';
            }

            // be ready for MAGIC!
            $keys = array();
            foreach ( $this as $item ) {
                $keys[] = $item->$foreignKey;
            }

            $finder = New $finderClass;
            $objects = $finder->$finderMethod( $keys ); 
            foreach ( $objects as $object ) { 
                $objectsByKey[ $object->Id ] = $object;
            }

            foreach ( $this as $i => $item ) {
                if ( !isset( $objectsByKey[ $item->$foreignKey ] ) ) {
                    continue;
                }
                    
                $item->CopyRelationFrom( $relationAttribute, $objectsByKey[ $item->$foreignKey ] );
                $this[ $i ] = $item; // thank god this works
            }
        }
        public function TotalCount() {
            return $this->mTotalCount;
        }
        public function ToArray() {
            $data = array();
            foreach ( $this as $item ) {
                $data[] = $item;
            }
            return $data;
        }
        protected function ToArrayById() {
            $data = array();
            foreach ( $this as $item ) {
                $data[ $item->Id ] = $item;
            }
            return $data;
        }
        public function __construct( $data, $totalcount = false ) {
            w_assert( is_array( $data ) );
            if ( $totalcount === false ) {
                $this->mTotalCount = count( $data );
            }
            else {
                w_assert( is_int( $totalcount ) );
                $this->mTotalCount = $totalcount;
            }
            parent::__construct( $data );
        }
    }
?>
