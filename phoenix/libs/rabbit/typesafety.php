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
    
    class tInteger extends tBaseType {
        public function tInteger( $value ) {
            $this->mValue = ( integer )$value;
            $this->tBaseType( $value );
        }
    }
    
    class tFloat extends tBaseType {
        public function tFloat( $value ) {
            $this->mValue = ( float )$value;
            $this->tBaseType( $value );
        }
    }
    
    class tBoolean extends tBaseType {
        public function tBoolean( $value ) {
            if ( $value === 'yes' || $value === 'true' || $value === '1' || $value === 1 ) {
                $this->mValue = true;
            }
            else if ( $value === 'no' || $value === 'false' || $value === '0' || $value === 0 ) {
                $this->mValue = false;
            }
            else {
                $this->mValue = ( bool )$value;
            }
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
            if ( empty( $values ) ) { // false
                return;
            }
            if ( !is_array( $values ) ) {
                // single array value
                $values = array( $values );
            }
            foreach ( $values as $value ) {
                $this->mValues[] = New $basetype( $value ); // MAGIC!
            }
        }
        public function rewind() {
            return reset($this->mValues);
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
            throw New Exception( 'Type Get() cannot be used on tArray; iterate over tArray and ->Get() on each value instead' );
        }
    }
    
    class tIntegerArray extends tArray {
        public function tIntegerArray( $values ) {
            $this->tArray( $values, 'tInteger' );
        }
    }

    class tFloatArray extends tArray {
        public function tFloatArray( $values ) {
            $this->tArray( $values, 'tFloat' );
        }
    }
    
    class tBooleanArray extends tArray {
        public function tBooleanArray( $values ) {
            $this->tArray( $values, 'tBoolean' );
        }
    }

    class tStringArray extends tArray {
        public function tStringArray( $values ) {
            $this->tArray( $values, 'tString' );
        }
    }

    class tCoalaPointer extends tString {
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
            throw New Exception( 'Type Get() cannot be used on tCoalaPointer; use "echo" directly with your pointer instead' );
        }
        public function __toString() {
            return $this->mValue;
        }
    }
    
    class tFile extends tBaseType {
        private $mName;
        private $mMimeType;
        private $mSize;
        private $mTempName;
        private $mErrorCode;
        private $mExists;
        
        public function Exists() {
            return $this->mExists;
        }
        public function __get( $name ) {
            switch ( $name ) {
                case 'Name':
                    return $this->mName;
                case 'MimeType':
                    return $this->mMimeType;
                case 'Size':
                    return $this->mSize;
                case 'TempName':
                    return $this->mTempName;
                case 'ErrorCode':
                    return $this->mErrorCode;
            }
            // else return nothing
        }
        public function tFile( $value ) {
            $this->mExists = false;
            if ( !is_array( $value ) ) {
                return;
            }
            if ( !isset( $value[ 'tmp_name' ] ) ) {
                return;
            }
            if ( !is_uploaded_file( $value[ 'tmp_name' ] ) ) {
                return;
            }
            if ( !isset( $value[ 'name' ] ) ) {
                $value[ 'name' ] = '';
            }
            if ( !isset( $value[ 'type' ] ) ) {
                $value[ 'type' ] = '';
            }
            if ( !isset( $value[ 'size' ] ) ) {
                $value[ 'size' ] = 0;
            }

            $this->mExists    = true;
            $this->mName      = $value[ 'name' ];
            $this->mMimeType  = $value[ 'type' ]; // mime type, if the browser provided such information
            $this->mSize      = $value[ 'size' ]; // in bytes
            $this->mTempName  = $value[ 'tmp_name' ];
            $this->mErrorCode = $value[ 'error' ];
        }
        public function __toString() {
            return '[uploaded file: ' . $this->mName . ']';
        }
        public function Get() {
            throw New Exception( 'Type Get() cannot be used on tFile; use build-in methods and attributes directly with your file object instead' );
        }
    }

    function Rabbit_TypeSafe_Call( $function , $req ) {
        w_assert( is_array( $req ) );
        
        // reflect!
        $basetype = New ReflectionClass( 'tBaseType' );
        $func = New ReflectionFunction( $function );
        $params = array();
        
        foreach ( $func->GetParameters() as $i => $parameter ) {
            $paramname = $parameter->getName();
            $paramclass = $parameter->getClass();
            if ( !is_object( $paramclass ) ) {
                throw New Exception( 'No type hinting specified for parameter ' . $paramname . ' of type-safe function ' . $function );
            }
            else {
                if ( !$paramclass->isSubclassOf( $basetype ) ) {
                    throw New Exception( 'Type hint of parameter ' . $paramname . ' of type-safe function ' . $function . ' does not exist or is not derived from tBaseType' );
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
