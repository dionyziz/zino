<?php
    class TestRabbitWater extends Testcase {
        protected $mAppliesTo = 'libs/rabbit/water';
        protected $mWater;
        
        public function TestVariablesExist() {
            $this->Assert( isset( $GLOBALS[ 'water' ] ) );
            $this->mWater = $GLOBALS[ 'water' ];
            $this->Assert( $this->mWater instanceof Water );
        }
        public function TestFunctionsExist() {
            $this->Assert( function_exists( 'w_assert' ), 'w_assert function does not exist' );
        }
        public function TestMethodsExist() {
            $this->Assert( method_exists( $this->mWater, 'Trace' ), 'Water->Trace() method doesn\'t exist' );
            $this->Assert( method_exists( $this->mWater, 'Notice' ), 'Water->Notice() method doesn\'t exist' );
            $this->Assert( method_exists( $this->mWater, 'Warning' ), 'Water->Warning() method doesn\'t exist' );
            $this->Assert( method_exists( $this->mWater, 'Error' ), 'Water->Error() method doesn\'t exist' );
        }
        public function TestExceptions() {
            $caught = false;
            try {
                throw New Exception( 'test' );
            }
            catch ( Exception $e ) {
                $caught = true;
            }
            $this->AssertTrue( $caught, 'Could not catch a simple exception' );
        }
        public function TestAssertions() {
            $caught = false;
            try {
                w_assert( true );
            }
            catch ( ExceptionFailedAssertion $e ) {
                $caught = true;
            }
            $this->AssertFalse( $caught, 'A true assertion should not cause an exception' );
            $caught = false;
            try {
                w_assert( false, 'Hello, world' );
            }
            catch ( ExceptionFailedAssertion $e ) {
                $caught = true;
                $this->AssertEquals( $e->getMessage(), 'Assertion failed: Hello, world', 'Failed assertion exceptions should maintain failure reason' );
            }
            $this->AssertTrue( $caught, 'A false assertion should cause an exception' );
        }
    }
    
    return New TestRabbitWater();
?>
