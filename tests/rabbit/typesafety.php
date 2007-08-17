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
        public function TestBaseTypesExist() {
            $this->Assert( class_exists( 'tBaseType'     ), 'tBaseType class does not exist'     );
            $this->Assert( class_exists( 'tInteger'      ), 'tInteger class does not exist'      );
            $this->Assert( class_exists( 'tFloat'        ), 'tFloat class does not exist'        );
            $this->Assert( class_exists( 'tBoolean'      ), 'tBoolean class does not exist'      );
            $this->Assert( class_exists( 'tString'       ), 'tString class does not exist'       );
            $this->Assert( class_exists( 'tCoalaPointer' ), 'tCoalaPointer class does not exist' );
        }
        public function TestFunctionsExist() {
            $this->Assert( function_exists( 'Rabbit_TypeSafe_Call' ), 'Rabbit_TypeSafe_Call function does not exist' );
        }
        public function TestTypesafeCall() {
            unset( $GLOBALS[ 'TestRabbitTypeSafety_ExampleNoArguments' ] );
            $this->AssertEquals( 'example', Rabbit_TypeSafe_Call( 'TestRabbitTypeSafety_ExampleNoArguments' ) );
            $this->AssertNotNull( $GLOBALS[ 'TestRabbitTypeSafety_ExampleNoArguments' ], 'Rabbit_TypeSafe_Call does not invoke function' );
            unset( $GLOBALS[ 'TestRabbitTypeSafety_ExampleNoArguments' ] );
            $this->AssertEquals( 6, Rabbit_TypeSafe_Call( 'TestRabbitTypeSafety_ExampleInteger', 5 ) );
            $this->AssertEquals( '.hello', Rabbit_TypeSafe_Call( 'TestRabbitTypeSafety_ExampleString', 'hello' ) );
            $this->AssertEquals( true, Rabbit_TypeSafe_Call( 'TestRabbitTypeSafety_ExampleBoolean', false ) );
            $this->AssertEquals( false, Rabbit_TypeSafe_Call( 'TestRabbitTypeSafety_ExampleBoolean', true ) );
            $this->AssertEquals( 1.1, Rabbit_TypeSafe_Call( 'TestRabbitTypeSafety_ExampleFloat', 0.6 ) );
            $this->AssertEquals( 
                array( 3, 0.2, false, 't' ), 
                Rabbit_TypeSafe_Call( 'TestRabbitTypeSafety_ExampleMultiple', array( 3, 0.2, false, 't' ) ) 
            );
            ob_start();
            echo Rabbit_TypeSafe_Call( 'TestRabbitTypeSafety_CoalaPtr', 'xxx' );
            $this->AssertEquals( ob_get_clean(), 'xxx' );
        }
    }
    
    return New TestRabbitTypeSafety();
?>
