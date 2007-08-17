<?php
    abstract class tBaseType {
        protected $mValue;
        
        public function tBaseType( $value ) {
        }
        public function Get() {
            return $this->mValue;
        }
        public function __toString() {
            return ( string )$this->Get();
        }
    }
    
    final class tInteger extends tBaseType {
        public function tInteger( $value ) {
            $this->mValue = ( integer )$value;
            $this->tBaseType( $value );
        }
    }
    
    final class tFloat extends tBaseType {
        public function tFloat( $value ) {
            $this->mValue = ( float )$value;
            $this->tBaseType( $value );
        }
    }
    
    final class tBoolean extends tBaseType {
        public function tBoolean( $value ) {
            $this->mValue = ( bool )$value;
            $this->tBaseType( $value );
        }
    }
    
    class tString extends tBaseType {
        public function tString( $value ) {
            $this->mValue = ( string )$value;
            $this->tBaseType( $value );
        }
    }
    
    abstract class tArray extends tBaseType implements Iterator {
        protected $mValues;
        
        public function tArray( $values, $basetype ) {
            w_assert( is_string( $basetype ), '$basetype, second parameter to tArray constructor from your custom type, must be a string' );
            w_assert( class_exists( $basetype ), '$basetype, second parameter to tArray constructor from your custom type, cannot be the empty string' );
            
            $baseclass = New ReflectionClass( $basetype );
            
            w_assert( $baseclass->isSubclassOf( New ReflectionClass( 'tBaseType' ) ), '$basetype, second parameter to tArray constructor from your custom type-safe type, is expected to be a string of a class name derived from tBaseType' );
            
            $this->mValues = array();
            foreach ( $values as $value ) {
                $this->mValues[] = New $basetype( $value ); // MAGIC!
            }
        }
        public function rewind() {
            return reset($this->var);
        }
        public function current() {
            return current($this->mValues);
        }
        public function key() {
            return key($this->mValues);
        }
        public function next() {
            return next($this->mValues);
        }
        public function valid() {
            return $this->current() !== false;
        }
        public function Get() {
            global $water;
            
            $water->ThrowException( 'Type Get() cannot be used on tArray; iterate over tArray and ->Get() on each value instead' );
        }
    }
    
    final class tIntegerArray extends tArray {
        public function tIntegerArray( $values ) {
            $this->tArray( $values, 'tInteger' );
        }
    }

    final class tFloatArray extends tArray {
        public function tFloatArray( $values ) {
            $this->tArray( $values, 'tFloat' );
        }
    }
    
    final class tBooleanArray extends tArray {
        public function tBooleanArray( $values ) {
            $this->tArray( $values, 'tBoolean' );
        }
    }

    final class tStringArray extends tArray {
        public function tStringArray( $values ) {
            $this->tArray( $values, 'tString' );
        }
    }

    final class tCoalaPointer extends tString {
        private $mExists;
        
        public function tCoalaPointer( $value ) {
            $this->tString( $value );
            $this->mExists = $value != '0';
            w_assert( preg_match( '#^([a-zA-Z0-9\.\[\] ])+$#', $this->mValue ) );
        }
        public function Exists() {
            return $this->mExists;
        }
        public function Get() {
            global $water;
            
            $water->ThrowException( 'Type Get() cannot be used on tCoalaPointer; use "echo" directly with your pointer instead' );
        }
        public function __toString() {
            return $this->mValue;
        }
    }

    function Rabbit_TypeSafe_Call( $function , $req ) {
        global $water;
        
        // reflect!
        $basetype = New ReflectionClass( 'tBaseType' );
        $func = New ReflectionFunction( $function );
        $params = array();
        
        foreach ( $func->GetParameters() as $i => $parameter ) {
            $paramname = $parameter->getName();
            $paramclass = $parameter->getClass();
            if ( !is_object( $paramclass ) ) {
                $water->ThrowException( 'No type hinting specified for parameter ' . $paramname . ' of type-safe function ' . $function );
            }
            else {
                if ( !$paramclass->isSubclassOf( $basetype ) ) {
                    $water->ThrowException( 'Type hint of parameter ' . $paramname . ' of type-safe function ' . $function . ' does not exist or is not derived from tBaseType' );
                }
                if ( isset( $req[ $paramname ] ) ) {
                    $params[] = $paramclass->newInstance( $req[ $paramname ] );
                }
                else {
                    $params[] = $paramclass->newInstance( false );
                }
            }
        }
        
        return call_user_func_array( $function , $params );
    }
?>
