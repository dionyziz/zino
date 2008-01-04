<?php
    final class TestRabbitUnittesting extends Testcase {
        protected $mAppliesTo = 'libs/rabbit/unittest';
        
        public function TestAssertionsExist() {
            $this->Assert( method_exists( $this, 'Assert'          ), 'Testcase::Assert method does not exist'          ); // ha!
            $this->Assert( method_exists( $this, 'AssertTrue'      ), 'Testcase::AssertTrue method does not exist'      );
            $this->Assert( method_exists( $this, 'AssertFalse'     ), 'Testcase::AssertFalse method does not exist'     );
            $this->Assert( method_exists( $this, 'AssertNull'      ), 'Testcase::AssertNull method does not exist'      );
            $this->Assert( method_exists( $this, 'AssertNotNull'   ), 'Testcase::AssertNotNull method does not exist'   );
            $this->Assert( method_exists( $this, 'AssertEquals'    ), 'Testcase::AssertEquals method does not exist'    );
            $this->Assert( method_exists( $this, 'AssertNotEquals' ), 'Testcase::AssertNotEquals method does not exist' );
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
            $this->Assert( class_exists( 'RunResult'      ), 'RunResult class does not exist'      );
            $this->Assert( class_exists( 'AssertResult'   ), 'AssertResult class does not exist'   );
        }
        /*
        public function TestAssertResult() {
            $bad  = New AssertResult( false, 'hello', 'actual string 1', 'expected string 1' );
            $good = New AssertResult( true,  'world', 'actual string 2', 'expected string 2' );
            $this->Assert( method_exists( $good, 'Success'  ), 'AssertResult::Success method does not exist'  );
            $this->Assert( method_exists( $good, 'Message'  ), 'AssertResult::Message method does not exist'  );
            $this->Assert( method_exists( $good, 'Actual'   ), 'AssertResult::Actual method does not exist'   );
            $this->Assert( method_exists( $good, 'Expected' ), 'AssertResult::Expected method does not exist' );
            $this->AssertFalse( $bad->Success, 'Unsuccessful assertion indicated as successful' );
            $this->AssertTrue( $good->Success, 'Successful assertion indicated as non-successful' );
            $this->AssertEquals( 'hello', $bad->Message, 'Unable to retrieve unsuccessful assertion message' );
            $this->AssertEquals( 'world', $good->Message, 'Unable to retrieve successful assertion message' );
            $this->AssertEquals( 'actual string 1', $bad->Actual, 'Unable to retrieve unsuccessful assertion actual value' );
            $this->AssertEquals( 'actual string 2', $good->Actual, 'Unable to retrieve successful assertion actual value' );
            $this->AssertEquals( 'expected string 1', $bad->Expected, 'Unable to retrieve unsuccessful assertion expected value' );
            $this->AssertEquals( 'expected string 2', $good->Expected, 'Unable to retrieve successful assertion expected value' );
        }
        public function TestRunResult() {
            $bad  = New AssertResult( false, 'hello', 'actual string 1', 'expected string 1' );
            $good = New AssertResult( true,  'world', 'actual string 2', 'expected string 2' );
            $runresult = New RunResult( array( $bad, $good, $bad, $bad ), 'run1' );
            $this->Assert( method_exists( $runresult, 'Success'                 ), 'RunResult::Success method does not exist' );
            $this->Assert( method_exists( $runresult, 'RunName'                 ), 'RunResult::RunName method does not exist' );
            $this->Assert( method_exists( $runresult, 'NumAssertions'           ), 'RunResult::NumAssertions method does not exist' );
            $this->Assert( method_exists( $runresult, 'NumSuccessfulAssertions' ), 'RunResult::NumSuccessfulAssertions method does not exist' );
            $this->Assert( $runresult instanceof Iterator, 'RunResult must be iteratable' );
            
            $this->AssertEquals( $runresult->RunName, 'run1' );
            $i = 0;
            foreach ( $runresult as $assertresult ) {
                switch ( $i ) {
                    case 0:
                    case 2:
                    case 3:
                        $this->AssertEquals( $assertresult, $bad, 'RunResult returns wrong assertions or assertions not in the order specified (slot ' . $i . ' has incorrect data)' );
                        break;
                    case 1:
                        $this->AssertEquals( $assertresult, $good, 'RunResult returns wrong assertions or assertions not in the order specified (slot ' . $i . ' has incorrect data)' );
                }
                ++$i;
            }
            $this->AssertEquals( 4, $i, 'Number of iterations does not match number of assertions in RunResult' );
            $this->AssertEquals( 4, $runresult->NumAssertions, 'Number of assertion returned does not match actual number of assertions in RunResult' );
            $this->AssertEquals( 1, $runresult->NumSuccessfulAssertions, 'Number of successful assertions returned does not match actual number of successful assertions in RunResult' );
            $this->AssertFalse( $runresult->Success );

            $runresult2 = New RunResult( array( $good, $good ), 'run2' );
            $this->AssertTrue( $runresult2->Success, 'RunResult with only successful assertions must be successful' );
            $this->AssertEquals( $runresult2->NumSuccessfulAssertions, $runresult2->NumAssertions, 'Number of successful assertions in successful RunResult must equal the total number of assertions' );
        }
        public function TestTestcaseResult() {
            $bad  = New AssertResult( false, 'hello', 'actual string 1', 'expected string 1' );
            $good = New AssertResult( true,  'world', 'actual string 2', 'expected string 2' );
            $badresult  = New RunResult( array( $bad, $good, $bad, $bad ), 'run1' ); // failure
            $goodresult = New RunResult( array( $good, $good ), 'run2' ); // success
            $testresult = New TestcaseResult( $this, array( $badresult, $goodresult ) ); // failure
            $testresult2 = New TestcaseResult( $this, array( $goodresult, $goodresult, $goodresult ) ); // success
            
            $this->Assert( method_exists( $testresult, 'Success'           ), 'TestcaseResult::Success method does not exist'           );
            $this->Assert( method_exists( $testresult, 'NumAssertions'     ), 'TestcaseResult::NumAssertions method does not exist'     );
            $this->Assert( method_exists( $testresult, 'NumRuns'           ), 'TestcaseResult::NumRuns method does not exist'           );
            $this->Assert( method_exists( $testresult, 'NumSuccessfulRuns' ), 'TestcaseResult::NumSuccessfulRuns method does not exist' );
            $this->Assert( method_exists( $testresult, 'Results'           ), 'TestcaseResult::Results method does not exist'           );
            $this->Assert( method_exists( $testresult, 'Testcase'          ), 'TestcaseResult::Testcase method does not exist'          );
            
            $this->AssertEquals( $this, $testresult->Testcase, 'Testcase rerturned by TestResult does not match the one passed' );
            $this->AssertFalse( $testresult->Success, 'Testcase with unsuccessful testruns incorrectly marked as successful' );
            $this->AssertTrue( $testresult2->Success, 'Testcase with successful testruns incorrectly marked as unsuccessful' );
            $this->AssertEquals( $badresult->NumAssertions + $goodresult->NumAssertions, $testresult->NumAssertions, 'Number of assertions returned by TestcaseResult does not match the sum of the relevant test RunResults' );
            $this->AssertEquals( 1, $testresult->NumSuccessfulRuns, 'Number of successful runs in TestcaseResult is inaccurate' );
            $this->AssertEquals( 2, $testresult->NumRuns, 'Total number of runs in TestcaseResult is inaccurate' );
            $this->AssertEquals( array( $badresult, $goodresult ), $testresult->Results(), 'Results returned by TestcaseResult do not match the ones passed to it' );
            
            $this->Assert( $testresult instanceof Iterator, 'TestcaseResult must be iteratable' );
            $i = 0;
            foreach ( $testresult as $runresult ) {
                switch ( $i ) {
                    case 0:
                        $this->AssertEquals( $badresult, $runresult, 'TestcaseResult returns wrong runs or runs not in the order specified (slot ' . $i . ' has incorrect data)' );
                        break;
                    case 1:
                        $this->AssertEquals( $goodresult, $runresult, 'TestcaseResult returns wrong runs or runs not in the order specified (slot ' . $i . ' has incorrect data)' );
                        break;
                }
                ++$i;
            }
            $this->AssertEquals( 2, $i, 'Number of iterations does not match number of runs in TestcaseResult' );
        }
        */
    }
    
    return New TestRabbitUnittesting();
?>
