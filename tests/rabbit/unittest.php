<?php
    final class TestRabbitUnittesting extends Testcase {
        public function TestAssertionsExist() {
            $this->Assert( method_exists( $this, 'Assert'          ), 'Testcase::Assert function does not exist'          ); // ha!
            die( 'TestAssertionsExist!!!' );
            $this->Assert( method_exists( $this, 'AssertTrue'      ), 'Testcase::AssertTrue function does not exist'      );
            $this->Assert( method_exists( $this, 'AssertFalse'     ), 'Testcase::AssertFalse function does not exist'     );
            $this->Assert( method_exists( $this, 'AssertNull'      ), 'Testcase::AssertNull function does not exist'      );
            $this->Assert( method_exists( $this, 'AssertNotNull'   ), 'Testcase::AssertNotNull function does not exist'   );
            $this->Assert( method_exists( $this, 'AssertEquals'    ), 'Testcase::AssertEquals function does not exist'    );
            $this->Assert( method_exists( $this, 'AssertNotEquals' ), 'Testcase::AssertNotEquals function does not exist' );
        }
        public function TestAssertions() {
            $this->Assert( true, 'Truth could not be asserted' );
            $this->Assert( 5, 'Positive integer should yield to true assertion' );
            $this->Assert( 'hello', 'Non-empty string should yield to true assertion' );
            $this->AssertTrue( true, 'AssertTrue should succeed with the true parameter' );
            $this->AssertFalse( false, 'AssertFalse should succeed with the false parameter' );
            $this->AssertNull( null, 'AssertNull should succeed with the null parameter' );
            $this->AssertNotNull( 5, 'AssertNotNull should succeed with a positive integer parameter' );
            $this->AssertNotNull( -5, 'AssertNotNull should succeed with a negative integer parameter' );
            $this->AssertNotNull( 0, 'AssertNotNull should succeed with the integer 0 parameter' );
            $this->AssertNotNull( 'hello', 'AssertNotNull should succeed with a non-empty string parameter' );
            $this->AssertNotNull( '', 'AssertNotNull should succeed with the empty string parameter' );
            $this->AssertNotNull( true, 'AssertNotNull should succeed with the boolean true parameter' );
            $this->AssertNotNull( false, 'AssertNotNull should succeed with the boolean false parameter' );
            $this->AssertNotNull( array( true ), 'AssertNotNull should succeed with a non-empty array parameter' );
            $this->AssertNotNull( array(), 'AssertNotNull should succeed with the empty array parameter' );
            $this->AssertNull( $foobar, 'AssertNull should succeed with a non-defined variable' );
            $foobar = null;
            $this->AssertNull( $foobar, 'AssertNull should succeed with a null variable' );
            $this->AssertNull( $GLOBALS[ 'foobar' ], 'AssertNull should succeed with a non-defined array index in $GLOBALS' );
            $foobar = array();
            $this->AssertNull( $foobar[ 'foobar' ], 'AssertNull should succeed with a non-defined array index' );
        }
        public function TestClassesExist() {
            $this->Assert( class_exists( 'Testcase'       ), 'Testcase class does not exist'       );
            $this->Assert( class_exists( 'Tester'         ), 'Tester class does not exist'         );
            $this->Assert( class_exists( 'TestcaseResult' ), 'TestcaseResult class does not exist' );
            $this->Assert( class_exists( 'TestResult'     ), 'TestResult class does not exist'     );
        }
    }
    
    return New TestRabbitUnittesting();
?>
