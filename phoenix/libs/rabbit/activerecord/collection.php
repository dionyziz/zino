<?php
    class Collection extends ArrayIterator {
        protected $mTotalCount;

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
