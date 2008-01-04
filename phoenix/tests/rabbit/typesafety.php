<?php
    function TestRabbitTypeSafety_ExampleNoArguments() {
        $GLOBALS[ 'TestRabbitTypeSafety_ExampleNoArguments' ] = true;
        
        return 'example';
    }
    function TestRabbitTypeSafety_ExampleInteger( tInteger $int ) {
        return $int->Get() + 1;
    }
    function TestRabbitTypeSafety_ExampleString( tString $str ) {
        return '.' . $str->Get();
    }
    function TestRabbitTypeSafety_ExampleBoolean( tBoolean $bool ) {
        return !$bool->Get();
    }
    function TestRabbitTypeSafety_ExampleFloat( tFloat $float ) {
        return $float->Get() + 0.5;
    }
    function TestRabbitTypeSafety_ExampleMultiple( tInteger $int, tFloat $float, tBoolean $bool, tString $str ) {
        return array( $int->Get(), $float->Get(), $bool->Get(), $str->Get() );
    }
    function TestRabbitTypeSafety_CoalaPtr( tCoalaPointer $ptr ) {
        echo $ptr;
    }
    
    final class TestRabbitTypeSafety extends Testcase {
        protected $mAppliesTo = 'libs/rabbit/typesafety';
        
        public function TestBaseTypesExist() {
            $this->Assert( class_exists( 'tBaseType'     ), 'tBaseType class does not exist'     );
            $this->Assert( class_exists( 'tInteger'      ), 'tInteger class does not exist'      );
            $this->Assert( class_exists( 'tFloat'        ), 'tFloat class does not exist'        );
            $this->Assert( class_exists( 'tBoolean'      ), 'tBoolean class does not exist'      );
            $this->Assert( class_exists( 'tString'       ), 'tString class does not exist'       );
            $this->Assert( class_exists( 'tCoalaPointer' ), 'tCoalaPointer class does not exist' );
            $this->Assert( class_exists( 'tArray'        ), 'tArray class does not exist'        );
            $this->Assert( class_exists( 'tIntegerArray' ), 'tIntegerArray class does not exist' );
            $this->Assert( class_exists( 'tFloatArray'   ), 'tFloatArray class does not exist'   );
            $this->Assert( class_exists( 'tBooleanArray' ), 'tBooleanArray class does not exist' );
            $this->Assert( class_exists( 'tStringArray'  ), 'tStrinArray class does not exist'   );
        }
        public function TestBaseTypesDefaults() {
            $int = New tInteger( '' );
            $this->Assert( is_int( $int->Get() ), 'tInteger should scale to integer' );
            $this->AssertEquals( 0, $int->Get(), 'Default tInteger value should be integer 0' );
            $float = New tFloat( '' );
            $this->Assert( is_float( $float->Get() ), 'tFloat should scale to float' );
            $this->AssertEquals( ( float )0, $float->Get(), 'Default tFloat value should be float 0' );
            $boolean = New tBoolean( '' );
            $this->Assert( is_bool( $boolean->Get() ), 'tBoolean should scale to boolean' );
            $this->AssertEquals( false, $boolean->Get(), 'Default tBoolean value should be boolean false' );
            $string = New tString( '' );
            $this->Assert( is_string( $string->Get() ), 'tString should scale to string' );
            $this->AssertEquals( '', $string->Get(), 'Default tString value should be the empty string' );
        }
        public function TestBoolean() {
            $boolean = New tBoolean( '' );
            $this->AssertEquals( false, $boolean->Get(), 'tBoolean constructed over the empty string should scale to boolean false' );
            $boolean = New tBoolean( false );
            $this->AssertEquals( false, $boolean->Get(), 'tBoolean constructed over boolean false should scale to boolean false' );
            $boolean = New tBoolean( '0' );
            $this->AssertEquals( false, $boolean->Get(), 'tBoolean constructed over string "0" should scale to boolean false' );
            $boolean = New tBoolean( 0 );
            $this->AssertEquals( false, $boolean->Get(), 'tBoolean constructed over integer 0 should scale to boolean false' );
            $boolean = New tBoolean( 'no' );
            $this->AssertEquals( false, $boolean->Get(), 'tBoolean constructed over string "no" should scale to boolean false' );
            $boolean = New tBoolean( 'false' );
            $this->AssertEquals( false, $boolean->Get(), 'tBoolean constructed over string "false" should scale to boolean false' );
            $boolean = New tBoolean( true );
            $this->AssertEquals( true, $boolean->Get(), 'tBoolean constructed over boolean true should scale to boolean true' );
            $boolean = New tBoolean( '1' );
            $this->AssertEquals( true, $boolean->Get(), 'tBoolean constructed over string "1" should scale to boolean true' );
            $boolean = New tBoolean( 1 );
            $this->AssertEquals( true, $boolean->Get(), 'tBoolean constructed over integer 1 should scale to boolean true' );
            $boolean = New tBoolean( 'yes' );
            $this->AssertEquals( true, $boolean->Get(), 'tBoolean constructed over string "yes" should scale to boolean true' );
            $boolean = New tBoolean( 'true' );
            $this->AssertEquals( true, $boolean->Get(), 'tBoolean constructed over string "true" should scale to boolean true' );
        }
        public function TestStringToType() {
            $int = New tInteger( 'bwahahah' );
            $this->Assert( is_int( $int->Get() ), 'tInteger constructed over arbitrary string should scale to integer' );
            $this->AssertEquals( 0, $int->Get(), 'tInteger constructed over non-numeric string should scale to integer 0' );
            $float = New tFloat( 'hoho' );
            $this->Assert( is_float( $float->Get() ), 'tFloat constructed over arbitrary string should scale to float' );
            $this->AssertEquals( ( float )0, $float->Get(), 'tFloat constructed over arbitrary string should scale to float 0' );
        }
        public function TestFunctionsExist() {
            $this->Assert( function_exists( 'Rabbit_TypeSafe_Call' ), 'Rabbit_TypeSafe_Call function does not exist' );
        }
        public function TestTypesafeCall() {
            unset( $GLOBALS[ 'TestRabbitTypeSafety_ExampleNoArguments' ] );
            $this->AssertEquals( 'example', Rabbit_TypeSafe_Call( 'TestRabbitTypeSafety_ExampleNoArguments', array() ) );
            $this->AssertNotNull( $GLOBALS[ 'TestRabbitTypeSafety_ExampleNoArguments' ], 'Rabbit_TypeSafe_Call does not invoke function' );
            unset( $GLOBALS[ 'TestRabbitTypeSafety_ExampleNoArguments' ] );
            $this->AssertEquals( 6, Rabbit_TypeSafe_Call( 'TestRabbitTypeSafety_ExampleInteger', array( 'int' => 5 ) ) );
            $this->AssertEquals( '.hello', Rabbit_TypeSafe_Call( 'TestRabbitTypeSafety_ExampleString', array( 'str' => 'hello' ) ) );
            $this->AssertEquals( true, Rabbit_TypeSafe_Call( 'TestRabbitTypeSafety_ExampleBoolean', array( 'bool' => false ) ) );
            $this->AssertEquals( false, Rabbit_TypeSafe_Call( 'TestRabbitTypeSafety_ExampleBoolean', array( 'bool' => true ) ) );
            $this->AssertEquals( 1.1, Rabbit_TypeSafe_Call( 'TestRabbitTypeSafety_ExampleFloat', array( 'float' => 0.6 ) ) );
            $this->AssertEquals( 
                array( 3, 0.2, false, 't' ), 
                Rabbit_TypeSafe_Call( 'TestRabbitTypeSafety_ExampleMultiple', array( 'int' => 3, 'float' => 0.2, 'bool' => false, 'str' => 't' ) ) 
            );
            ob_start();
            echo Rabbit_TypeSafe_Call( 'TestRabbitTypeSafety_CoalaPtr', array( 'ptr' => 'xxx' ) );
            $this->AssertEquals( ob_get_clean(), 'xxx' );
        }
        public function TestArgumentOrder() {
            $this->AssertEquals( 0.5, Rabbit_TypeSafe_Call( 'TestRabbitTypeSafety_ExampleFloat', array( 'hohohoinvalid' => 0.6 ) ) );
            $this->AssertEquals( 
                array( 3, ( float )0, true, '' ), 
                Rabbit_TypeSafe_Call( 'TestRabbitTypeSafety_ExampleMultiple', array( 'int' => 3, 'bool' => true ) ) 
            );
            $this->AssertEquals(
                array( 0, ( float )0, false, '' ),
                Rabbit_TypeSafe_Call( 'TestRabbitTypeSafety_ExampleMultiple', array() )
            );
        }
    }
    
    return New TestRabbitTypeSafety();
?>
