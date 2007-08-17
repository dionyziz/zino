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
                New TestResult( is_null( $actual ), $message, $actual, null )
            );
        }
        protected function AssertNotNull( $actual, $message = '' ) {
            $this->InformTester(
                New TestResult( !is_null( $actual ), $message, $actual, null )
            );
        }
        protected function AssertEquals( $expected, $actual, $message = '' ) {
            $this->InformTester(
                New TestResult( $actual === $expected, $message, $actual, $expected )
            );
        }
        protected function AssertNotEquals( $notexpected, $actual, $message = '' ) {
            $this->InformTester(
                New TestResult( $actual != $expected, $message, $actual, $expected )
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
        protected $mTestcaseResults;
        protected $mTestcases;
        protected $mTestResults;
        
        public function AddTestcase( Testcase $testcase ) {
            $this->mTestcases[] = $testcase;
        }
        public function Run() {
            foreach ( $this->mTestcases as $i => $testcase ) {
                $obj = New ReflectionObject( $testcase );
                $methods = $obj->getMethods();
                $this->mTestcaseResults = array();
                foreach ( $methods as $method ) {
                    if ( $method->isPublic() && substr( $method->getName(), 0, strlen( 'Test' ) ) == 'Test' ) {
                        call_user_func( array( $obj, $method->getName() ) ); // MAGIC
                    }
                }
                $this->mTestResults[ $i ] = New TestcaseResult( $testcase, $this->mTestcaseResults );
            }
        }
        public function GetResults() {
            return $this->mTestResults;
        }
        public function Inform( TestResult $result ) {
            $this->mTestcaseResults[] = $result;
        }
    }
    
    class TestcaseResult {
        protected $mTestResults;
        protected $mTestcase;
        
        public function Testcase() {
            return $this->mTestcase;
        }
        public function Results() {
            return $this->mTestResults;
        }
        public function TestcaseResult( Testcase $testcase, array $testresults ) {
            foreach ( $testresults as $result ) {
                w_assert( $result instanceof TestResult );
            }
            $this->mTestcase = $testcase;
            $this->mTestResults = $testresults;
        }
    }
    
    class TestResult {
        protected $mResultCode;
        protected $mMessage;
        protected $mActual;
        protected $mExpected;
        
        public function Success() {
            return $this->mResultCode == RABBIT_TEST_SUCCESS;
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
        public function TestResult( $resultcode, $message, $actual, $expected ) {
            $this->mResultCode = $resultcode;
            $this->mMessage    = $message;
            $this->mActual     = $actual;
            $this->mExpected   = $expected;
        }
    }
?>
