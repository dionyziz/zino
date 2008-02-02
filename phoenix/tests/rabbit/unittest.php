<?php
    class TestRabbitUnittestingSimulation extends Testcase { // this is a simulation testcase
        protected $mAppliesTo = 'libs/rabbit/unittest';
        
        public function TestSuccessful() {
            $this->Assert( true, 'True is true' );
            $this->AssertEquals( 1, 1, '1 = 1' );
            $this->AssertFalse( false, 'False is false' );
        }
        public function TestFailed() {
            $this->Assert( true, 'True is true' );
            $this->Assert( false, 'We think we can assert false' );
            $this->AssertEquals( 1, 2, 'We think 1 = 2' );
            $this->AssertEquals( 2, 2, '2 = 2' );
        }
        public function TestDoomedToFailure() {
            $this->Assert( true, 'True is true' );
            throw New Exception( 'Unanticipated Failure' );
            $this->Assert( false, 'This will not execute anyway' );
        }
    }
    
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
            $this->AssertNull( $foobar, 'AssertNull should succeed with an undefined variable' );
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
        public function TestAssertResult() {
            $bad  = New AssertResult( false, 'hello', 'actual string 1', 'expected string 1' );
            $good = New AssertResult( true,  'world', 'actual string 2', 'expected string 2' );
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
            
            $this->AssertEquals( $this, $testresult->Testcase, 'Testcase rerturned by TestResult does not match the one passed' );
            $this->AssertFalse( $testresult->Success, 'Testcase with unsuccessful testruns incorrectly marked as successful' );
            $this->AssertTrue( $testresult2->Success, 'Testcase with successful testruns incorrectly marked as unsuccessful' );
            $this->AssertEquals( $badresult->NumAssertions + $goodresult->NumAssertions, $testresult->NumAssertions, 'Number of assertions returned by TestcaseResult does not match the sum of the relevant test RunResults' );
            $this->AssertEquals( 1, $testresult->NumSuccessfulRuns, 'Number of successful runs in TestcaseResult is inaccurate' );
            $this->AssertEquals( 2, $testresult->NumRuns, 'Total number of runs in TestcaseResult is inaccurate' );
            $this->AssertEquals( array( $badresult, $goodresult ), $testresult->Results, 'Results returned by TestcaseResult do not match the ones passed to it' );
            
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
        public function TestTester() {
            $tester = New Tester();
            $testcase = New TestRabbitUnittestingSimulation();
            $tester->AddTestcase( $testcase );
            $tester->Run();
            $testerresults = $tester->GetResults();
            $this->Assert( is_array( $testerresults ), 'Results returned by Tester must be an array' );
            $this->AssertEquals( 1, count( $testerresults ), 'Number of testcase results returned by Tester must match the number of testcases' );
            $caseresult = array_shift( $testerresults );
            $this->Assert( is_object( $caseresult ), 'An item of a Tester\'s results must be an object' );
            $this->Assert( $caseresult instanceof TestcaseResult, 'An item of a Tester\'s results must be a TestcaseResult' );
            $this->Assert( 3, $caseresult->NumRuns, 'Number of case runs must match number of runs' );
            $this->Assert( 1, $caseresult->NumSuccessfulRuns, 'Number of case successful runs is 1' );
            // $runresults = $caseresults->Results;
            $this->Assert( is_array( $runresults ), 'Case Results must be an array' );
            $this->AssertEquals( 3, count( $results ), 'Number of case results must match number of testruns' );
            $i = 0;
            foreach ( $runresults as $result ) {
                switch ( $i ) {
                    case 0:
                        $this->Assert( is_object( $result ), 'Each item of a case\'s results must be an object (0)' );
                        $this->Assert( $result instanceof RunResult, 'Each item of a case\'s results must be an instance of RunResult (0)' );
                        $this->AssertEquals( true, $result->Success, 'This test was successful; it should be reported as such' );
                        $this->AssertEquals( 3, $result->NumAssertions, 'The number of assertions for this test seem incorrect (0)' );
                        $this->AssertEquals( $result->NumAssertions, $this->NumSuccessfulAssertions, 'All assertions of this run were successful' );
                        $this->AssertEquals( 'Successful', $result->RunName, 'Runname of an item of Tester\'s results is invalid (0)' );
                        break;
                    case 1:
                        $this->Assert( is_object( $result ), 'Each item of a case\'s results must be an object (1)' );
                        $this->Assert( $result instanceof RunResult, 'Each item of a case\'s results must be an instance of RunResult (1)' );
                        $this->AssertEquals( false, $result->Success, 'This test failed; it should be reported as such' );
                        $this->AssertEquals( 4, $result->NumAssertions, 'The number of assertions for this test seem incorrect (1)' );
                        $this->AssertEquals( 2, $this->NumSuccessfulAssertions, 'Only 2 assertions of this run were successful' );
                        $this->AssertEquals( 'Failed', $result->RunName, 'Runname of an item of Tester\'s results is invalid (0)' );
                        break;
                    case 2:
                        $this->Assert( is_object( $result ), 'Each item of a case\'s results must be an object (2)' );
                        $this->Assert( $result instanceof RunResult, 'Each item of a case\'s results must be an instance of RunResult (2)' );
                        $this->Assert( $result instanceof FailedRunResult, 'This item of a a case\'s results must be an instance of FailedRunResult' );
                        $this->AssertEquals( false, $result->Success, 'This test was unanticipately unsuccessful; it should be reported as such' );
                        $this->AssertEquals( 'DoomedToFailure', $result->RunName, 'Runname of an item of Tester\'s results is invalid (2)' );
                        break;
                }
                ++$i;
            }
        }
    }
    
    return New TestRabbitUnittesting();
?>
