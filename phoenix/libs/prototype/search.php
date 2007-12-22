<?php

    final class SearchPrototypeProperty {
        private $mPrototype;
        private $mProperty;

        public function BiggerThan( $value ) {
            $this->mPrototype->$this->mProperty = array( ">", $value ); // MAGIC!
        }
        public function SmallerThan( $value ) {
            $this->mPrototype->$this->mProperty = array( "<", $value ); // MAGIC!
        }
        public function Not( $value ) {
            $this->mPrototype->$this->mProperty = array( "!=", $value ); // MAGIC!
        }
        public function Range( $min, $max ) {
            $this->mPrototype->$this->mProperty = array( "range", $min, $max ); // MAGIC!
        }
        public function SearchPrototypeField( $prototype, $property ) {
            $this->mPrototype   = $prototype;
            $this->mProperty    = $property;
        }
    }

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

            w_assert( isset( $this->mFields[ $key ] ), "Trying to set a non-existing prototype property" );

            $this->mValues[ $key ][] = $value;
        }
        public function __get( $key ) {
            return New SearchPrototypeProperty( $this, $key );
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
