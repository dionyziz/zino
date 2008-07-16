<?php
    // Overloadable class
    // Developer: Dionyziz
    // Warning: This code is used massively. A single pageload may invoke these functions more than 20,000 times.
    //          Any change you make, such as adding a variable may have dramatic effects in the performarce of the framework.
    //          Think before you type, and always benchmark. Thanks! :-)

    abstract class Overloadable {
        private $mMethods = false;

        public function __set( $name, $value ) {
            // check if a custom setter is specified
            $methodname = 'Set' . $name;
            if ( $this->mMethods === false ) {
                $this->mMethods = array_flip( get_class_methods( $this ) );
                die( print_r( $this->mMethods ) );
            }
            if ( isset( $this->mMethods[ $methodname ] ) ) {
                return $this->$methodname( $value ) !== false;
            }
            // else fallthru
            return false;
        }
        public function __get( $name ) {
            // check if a custom getter is specified
            $methodname = 'Get' . $name;
            if ( $this->mMethods === false ) {
                $this->mMethods = array_flip( get_class_methods( $this ) );
            }
            if ( isset( $this->mMethods[ $methodname ] ) ) {
                return $this->$methodname();
            }
            // else fallthru
            return null; // use null here because we want to allow custom getters to return literal boolean false
        }
    }
?>
