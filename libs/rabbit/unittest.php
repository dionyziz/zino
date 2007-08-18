<?php
    class Testcase {
        protected $mTester;
        protected $mName;
        
        public function Testcase() {
        }
        public function SetName( $name ) {
            w_assert( is_string( $name ) );
            w_assert( !empty( $name ) );
            $this->mName = $name;
        }
        public function Name() {
            return $this->mName;
        }
        protected function AssertNull( $actual, $message = '' ) {
            $this->InformTester(
                New AssertResult( is_null( $actual ), $message, $actual, null )
            );
        }
        protected function AssertNotNull( $actual, $message = '' ) {
            $this->InformTester(
                New AssertResult( !is_null( $actual ), $message, $actual, null )
            );
        }
        protected function AssertEquals( $expected, $actual, $message = '' ) {
            $this->InformTester(
                New AssertResult( $actual === $expected, $message, $actual, $expected )
            );
        }
        protected function AssertNotEquals( $notexpected, $actual, $message = '' ) {
            $this->InformTester(
                New AssertResult( $actual != $expected, $message, $actual, $expected )
            );
        }
        protected function Assert( $actual, $message = '' ) {
            return $this->AssertEquals( true, ( bool )$actual, $message ); // ==
        }
        protected function AssertTrue( $actual, $message = '' ) {
            return $this->AssertEquals( true, $actual, $message ); // ===
        }
        protected function AssertFalse( $actual, $message = '' ) {
            return $this->AssertEquals( false, $actual, $message ); // ===
        }
        protected function InformTester( TestResult $result ) {
            $this->mTester->Inform( $result );
        }
        public function SetTester( Tester $tester ) {
            $this->mTester = $tester;
        }
    }
    
    function Test_GetTestcases() { // fetch a list of all testcases
        global $rabbit_settings;
        
        $ret = array();
        
        $queue = array( $rabbit_settings[ 'rootdir' ] . '/tests' );
        while ( !empty( $queue ) ) {
            $item = array_pop( $queue );
            $dh = opendir( $item );
            while ( false !== ( $df = readdir( $dh ) ) ) {
                switch ( $df ) {
                    case '.':
                    case '..':
                        break;
                    default:
                        if ( is_dir( $item . '/' . $df ) ){
                            array_push( $queue, $item . '/' . $df );
                        }
                        else if ( substr( $df, -strlen( '.php' ) ) == '.php' ) {
                            $testcase = require $item . '/' . $df;
                            w_assert( $testcase instanceof Testcase, "File $item/$df is not a valid Rabbit Testcase" );
                            $testcase->SetName( substr( $item . '/' . $df, strlen( $rabbit_settings[ 'rootdir' ] . '/tests/' ), -strlen( '.php' ) ) );
                            $ret[] = $testcase;
                        }
                }
            }
        }
        
        return $ret;
    }
    
    class Tester {
        protected $mTestResults;
        protected $mTestcases;
        protected $mAssertResults;
        
        public function Tester() {
            $this->mTestcases = array();
        }
        public function AddTestcase( Testcase $testcase ) {
            $this->mTestcases[] = $testcase;
        }
        public function Run() {
            global $water;
            
            $water->Profile( 'Running ' . count( $this->mTestcases ) . ' testcases' );
            $this->mTestcaseResults = array();
            foreach ( $this->mTestcases as $i => $testcase ) {
                $water->Profile( 'Running testcase ' . $testcase->Name() );
                $obj = New ReflectionObject( $testcase );
                $methods = $obj->getMethods();
                $runresults = array();
                foreach ( $methods as $method ) {
                    if ( $method->isPublic() && substr( $method->getName(), 0, strlen( 'Test' ) ) == 'Test' ) {
                        $water->Profile( 'Running testrun ' . $method->getName() );
                        $this->mAssertResults = array();
                        call_user_func( array( $testcase, $method->getName() ) ); // MAGIC
                        $runresults[] = New RunResult( $this->mAssertResults, $method->getName() );
                        $water->ProfileEnd();
                    }
                }
                $this->mTestResults[ $i ] = New TestcaseResult( $testcase, $runresults );
                $water->ProfileEnd();
            }
            $water->ProfileEnd();
        }
        public function GetResults() {
            return $this->mTestResults;
        }
        public function Inform( AssertResult $result ) {
            $this->mAssertResults[] = $result;
        }
    }
    
    class TestcaseResult implements Iterator { // a group of run results, the results for a complete testcase
        protected $mRunResults;
        protected $mTestcase;
        protected $mSuccess;
        protected $mNumRuns;
        protected $mNumAssertions;
        
        public function Testcase() {
            return $this->mTestcase;
        }
        public function Results() {
            return $this->mRunResults;
        }
        public function rewind() {
            return reset( $this->mRunResults );
        }
        public function current() {
            return current( $this->mRunResults );
        }
        public function key() {
            return key( $this->mRunResults );
        }
        public function next() {
            return next( $this->mRunResults );
        }
        public function valid() {
            return $this->current() !== false;
        }
        public function NumRuns() {
            return $this->mNumRuns;
        }
        public function NumAssertions() {
            return $this->mNumAssertions;
        }
        public function Success() {
            return $this->mSuccess;
        }
        public function TestcaseResult( Testcase $testcase, array $runresults ) {
            $this->mNumRuns = count( $runresults );
            $this->mNumAssertions = 0;
            $this->mSuccess = true;
            foreach ( $runresults as $runresult ) {
                w_assert( $runresult instanceof RunResult );
                $this->mSuccess = $this->mSuccess && $runresult->Success();
                $this->mNumAssertions += $runresult->NumAssertions();
            }
            $this->mTestcase = $testcase;
            $this->mRunResults = $runresults;
        }
    }
    
    class RunResult implements Iterator { // a group of assertion results, a result of a test (function in the testcase class)
        protected $mAssertionResults;
        protected $mSuccess;
        protected $mRunName;
        protected $mNumAssertions;
        
        public function rewind() {
            return reset( $this->mAssertionResults );
        }
        public function current() {
            return current( $this->mAssertionResults );
        }
        public function key() {
            return key( $this->mAssertionResults );
        }
        public function next() {
            return next( $this->mAssertionResults );
        }
        public function valid() {
            return $this->current() !== false;
        }
        public function RunName() {
            return $this->mRunName;
        }
        public function Success() {
            return $this->mSuccess;
        }
        public function NumAssertions() {
            return $this->mNumAssertions;
        }
        public function RunResults( array $assertionresults, $runname ) {
            w_assert( is_string( $runname ) );
            w_assert( !empty( $runname ) );
            $this->mRunName = $runname;
            $this->mNumAssertions = count( $assertionresults );
            $this->mSuccess = true;
            foreach ( $assertionresults as $assertionresult ) {
                w_assert( $assertionresult instanceof AssertResult );
                $this->mSuccess = $this->mSuccess && $assertionresult->Success();
            }
            $this->mAssertionResults = $assertionresults;
        }
    }
    
    class AssertResult { // most basic test, a simple assertion
        protected $mSuccess;
        protected $mMessage;
        protected $mActual;
        protected $mExpected;
        
        public function Success() {
            return $this->mSuccess;
        }
        public function Message() {
            return $this->mMessage;
        }
        public function Actual() {
            return $this->mActual;
        }
        public function Expected() {
            return $this->mExpected;
        }
        public function AssertResult( $success, $message, $actual, $expected ) {
            $this->mSuccess  = $success;
            $this->mMessage  = $message;
            $this->mActual   = $actual;
            $this->mExpected = $expected;
        }
    }
?>
