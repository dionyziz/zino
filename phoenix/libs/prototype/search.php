<?php

    abstract class SearchPrototype {
        protected $mValues;
        protected $mObject;
        protected $mClass;
        protected $mReferences;
        protected $mFields;
        protected $mTable;

        public function __set( $key, $value ) {
            $methodname = 'Set' . $key;
            if ( method_exists( $this, $methodname ) ) {
                $this->$methodname( $value ); // MAGIC!
            }
            $this->mValues[ $key ] = $value;
        }
        public function GetValues() {
            return $this->mValues;
        }
        public function GetFields() {
            return $this->mFields;
        }
        public function GetTable() {
            return $this->mTable;
        }
        public function GetClass() {
            return $this->mClass;
        }
        public function GetReferences() {
            return $this->mReferences;
        }
        protected function SetFields( $fields ) {
            w_assert( is_array( $fields ) );

            $this->mFields = array();
            foreach ( $fields as $field => $property ) {
                $this->mFields[ $property ] = $field;
            }
        }
        protected function SetReferences( $refs ) {
            w_assert( is_array( $refs ) );

            $this->mReferences = $refs;
        }
        public function SearchPrototype() {
            $this->mObject = new $this->mClass; // MAGIC!

            if ( !is_array( $this->mReferences ) ) {
                $this->mReferences = array();
            }
        }
    }

?>
