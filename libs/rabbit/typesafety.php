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
            
            $water->ThrowException( 'Type Get() cannot be used tCoalaPointer; use "echo" directly with your pointer instead' );
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
