<?php
    // Overloadable class
    // Developer: Dionyziz
    // Warning: This code is used massively. A single pageload may invoke these functions more than 20,000 times.
    //          Any change you make, such as adding a variable may have dramatic effects in the performarce of the framework.
    //          Think before you type, and always benchmark. Thanks! :-)

    abstract class Overloadable {
        private $mCache;

        public function __set( $name, $value ) {
            // check if a custom setter is specified
            $methodname = 'Set' . $name;
            if ( !isset( $this->mCache[ $methodname ] ) ) {
                $this->mCache[ $methodname ] = method_exists( $this, $methodname );
            }
            if ( $this->mCache[ $methodname ] ) {
                return $this->$methodname( $value ) !== false;
            }
            // else fallthru
            return false;
        }
        public function __get( $name ) {
            // check if a custom getter is specified
            $methodname = 'Get' . $name;
            if ( !isset( $this->mCache[ $methodname ] ) ) {
                $this->mCache[ $methodname ] = method_exists( $this, $methodname );
            }
            if ( $this->mCache[ $methodname ] ) {
                return $this->$methodname();
            }
            // else fallthru
            return null; // use null here because we want to allow custom getters to return literal boolean false
        }
    }
?>
