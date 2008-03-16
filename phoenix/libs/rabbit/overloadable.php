<?php
    abstract class Overloadable {
        public function __set( $name, $value ) {
            // check if a custom setter is specified
            $methodname = 'Set' . $name;
            if ( method_exists( $this, $methodname ) ) {
                $success = call_user_func( array( $this, $methodname ), $value );
                if ( $success !== false ) {
                    return true;
                }
            }
            // else fallthru
            return false;
        }
        public function __get( $name ) {
            // check if a custom getter is specified
            $methodname = 'Get' . $name;
            if ( method_exists( $this, $methodname ) ) {
                $value = call_user_func( array( $this, $methodname ) );
                return $value;
            }
            // else fallthru
            return null; // use null here because we want to allow custom getters to return literal boolean false
        }
    }
?>
