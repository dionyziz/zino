<?php
    /*
        Developer: Dionyziz
    */
    
    abstract class Testcase {
        protected $mTester;
        protected $mName;
        protected $mAppliesTo;
        protected $mOnPreConditions;
        
        final public function Testcase() {
        }
        public function __get( $key ) {
            switch ( $key ) {
                case 'Name':
                    $attribute = 'm' . $key;
                    return $this->$attribute;
            }
        }
        public function __set( $key, $value ) {
            switch ( $key ) {
                case 'Name':
                    w_assert( is_string( $key ) );
                    w_assert( !empty( $key ) );
                    // fallthrough
                case 'Tester':
                    $attribute = 'm' . $key;
                    $this->$attribute = $value;
            }
        }
        public function SetUp() { // overridable
        }
        public function TearDown() { // overridable
        }
        public function PreConditions() { // overridable
        }
        public function StartPreConditions() {
            $this->mOnPreConditions = true;
            $this->PreConditions();
            $this->mOnPreConditions = false;
        }
        final public function AppliesTo() {
            return $this->mAppliesTo;
        }
        protected function AssertNull( $actual, $message = '' ) {
            return $this->InformTester(
                New AssertResult( is_null( $actual ), $message, $actual, null )
            );
        }
        protected function AssertNotNull( $actual, $message = '' ) {
            return $this->InformTester(
                New AssertResult( !is_null( $actual ), $message, $actual, null )
            );
        }
        protected function AssertEquals( $expected, $actual, $message = '' ) {
            return $this->InformTester(
                New AssertResult( $actual === $expected, $message, $actual, $expected )
            );
        }
        protected function AssertNotEquals( $notexpected, $actual, $message = '' ) {
            return $this->InformTester(
                New AssertResult( $actual != $expected, $message, $actual, $expected )
            );
        }
        protected function Assert( $actual, $message = '' ) {
            return $this->AssertEquals( true, ( bool )$actual, $message ); // ==
        }
        protected function AssertTrue( $actual, $message = '' ) {
            if ( !is_bool( $actual ) ) {
                return $this->InformTester(
                    New AssertResult( false, $message, $actual, true )
                );
            }
            if ( $actual != true ) {
                return $this->InformTester(
                    New AssertResult( false, $message, false, true )
                );
            }
        }
        protected function AssertFalse( $actual, $message = '' ) {
            if ( !is_bool( $actual ) ) {
                return $this->InformTester(
                    New AssertResult( false, $message, $actual, false )
                );
            }
            if ( $actual != false ) {
                return $this->InformTester(
                    New AssertResult( false, $message, true, false )
                );
            }
            return $this->InformTester(
                New AssertResult( true, $message, $actual, false )
            );
        }
        protected function AssertClassExists( $class, $message = false ) {
            if ( $message === false ) {
                $message = "Class $class does not exist";
            }
            $this->Assert( class_exists( $class ), $message );
        }
        protected function AssertMethodExists( $class, $method, $message = false ) {
            if ( $message === false ) {
                $message = "Method $class::$method does not exist";
            }
            $this->Assert( method_exists( $class, $method ), $message );
        }
        protected function AssertIsArray( $array, $message = '' ) {
            if ( empty( $message ) && $this->mCalled !== false ) {
                $message = $this->mCalled . ' did not return an array';
            }
            $this->AssertEquals( 'array', get_type( $array ), $message );
        }
        protected function AssertArrayHasKeys( $array, $keys, $message = '' ) {
            foreach ( $keys as $key ) {
                $this->AssertArrayHasKey( $array, $key, $message );
            }
        }
        protected function AssertArrayHasKey( $array, $key, $message = '' ) {
            if ( empty( $message ) && $this->mCalled !== false ) {
                $message = $this->mCalled . " did not return an array with key " . $key;
            }
            $this->Assert( isset( $array[ $key ] ), $message );
        }
        protected function AssertArrayValues( $array, $bykey, $message = '' ) {
            foreach ( $bykey as $key => $value ) {
                $this->AssertArrayValue( $array, $key, $value, $message = '' );
            }
        }
        protected function AssertArrayValue( $array, $key, $value, $message = '' ) {
            if ( empty( $message ) && $this->mCalled !== false ) {
                $message = $this->mCalled . " has wrong value for $key.";
            }
            $this->AssertEquals( $value, $array[ $key ], $message );
        }
        protected function RequireSuccess( AssertResult $result ) {
            if ( !$result->Success ) {
                $this->mTester->RequireFailed( $result );
            }
        }
        protected function InformTester( AssertResult $result ) {
            if ( $this->mOnPreConditions ) {
                $this->RequireSuccess( $result );
            }
            return $this->mTester->Inform( $result );
        }
        protected function Call( $function, $arguments ) {
            $ret = call_user_func_array( $function, $arguments );
            $this->mCalled = $function;
            return $ret;
        }
    }
    
    function Test_GetTestcases() { // fetch a list of all testcases
        global $settings;
        global $water;
        
        $ret = array();
        
        $queue = array( 'tests' );
        while ( !empty( $queue ) ) {
            $item = array_pop( $queue );
            if ( substr( $item, -4 ) == ".svn" ) {
                continue;
            }
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
                            if ( !( $testcase instanceof Testcase ) ) {
                                // $water->Warning( "File $item/$df is not a valid Rabbit Testcase; skipping" );
                            }
                            else {
                                $appliesto = $testcase->AppliesTo();
                                /*
                                $fileloadresult = Mask( $appliesto );
                                if ( isset( $fileloadresult[ 'error' ] ) ) {
                                    // $water->Warning( "Rabbit Testcase $item/$df did not specify a valid 'mAppliesTo' path; skipping" );
                                }
                                else {
                                */
                                $testcase->Name = substr( $item . '/' . $df, strlen( 'tests/' ), -strlen( '.php' ) );
                                $ret[] = $testcase;
                                /* } */
                            }
                        }
                }
            }
        }
        
        return $ret;
    }
    
    function Test_VarDump( $var ) {
        if ( is_scalar( $var ) ) {
            return var_dump( $var );
        }
        if ( is_object( $var ) ) {
            ?>[ <?php 
            echo get_class( $var );
            ?> object: <?php
            echo ( string )$var;
            ?> ]<?php
            return;
        }
        if ( is_array( $var ) ) {
            ?>[ array of <?php 
            echo count( $var );
            ?> items: <?php
            foreach ( $var as $key => $value ) {
                Test_VarDump( $key );
                ?> => <?php
                Test_VarDump( $value );
            }
            ?> ]<?php
        }
    }
    
    class Tester {
        protected $mTestResults;
        protected $mTestcases;
        protected $mAssertResults;
        protected $mRequirementsFullfilled;

        public function Tester() {
            $this->mTestcases = array();
        }
        public function AddTestcase( Testcase $testcase ) {
            $this->mTestcases[] = $testcase;
        }
        public function GetAnnotations( ReflectionMethod $method ) {
            $lines = explode( "\n", $method->getDocComment() );
            $annotations = array();
            foreach ( $lines as $line ) {
                $start = strpos( $line, '@' );
                if ( $start === false ) {
                    continue;
                }
                $nameEnd = strpos( $line, ' ', $start );
                $name = substr( $line, $start + 1, $nameEnd - $start - 1 );
                $argsLine = substr( $line, $nameEnd + 1 );
                $args = explode( " ", $argsLine );
                $annotations[ $name ] = $args;
            }
            return $annotations;
        }
        public function Run() {
            global $water;
            
            // $water->Profile( 'Running ' . count( $this->mTestcases ) . ' testcases' );
            $this->mTestcaseResults = array();
            foreach ( $this->mTestcases as $i => $testcase ) {
                // $water->Profile( 'Running testcase ' . $testcase->Name );
                $testcase->Tester = $this; // allows testcase to report results back to this tester
                // Rabbit_Include( $testcase->AppliesTo() );
                $obj = New ReflectionObject( $testcase );
                $methods = $obj->getMethods();
                $runresults = array();
                $goodtogo = true;
                try {
                    $testcase->SetUp();
                }
                catch ( Exception $e ) {
                    $runresults[] = New RunResult( array( New AssertResultFailedByException( $e->getMessage(), $e->getTrace() ) ), '[SetUp]' );
                    $goodtogo = false;
                }
                try {
                    $testcase->StartPreConditions();
                }
                catch ( Exception $e ) {
                    $runresults[] = New RunResult( array( New AssertResultFailedByException( $e->getMessage(), $e->getTrace() ) ), '[PreConditions]' );
                    $goodtogo = false;
                }
                if ( $goodtogo ) {
                    foreach ( $methods as $method ) {
                        $methodname = $method->getName();
                        if ( $method->isPublic() && substr( $methodname, 0, strlen( 'Test' ) ) == 'Test' && $methodname != 'Testcase' ) {

                            // $water->Profile( 'Running testrun ' . $methodname );
                            $annotations = $this->GetAnnotations( $method );
                            $dataProvided = false;
                            if ( isset( $annotations[ 'dataProvider' ] ) ) {
                                $provider = $annotations[ 'dataProvider' ][ 0 ];
                                $dataProvided = call_user_func( array( $testcase, $provider ) );
                                w_assert( is_array( $dataProvided ), 'dataprovider did not provide array' );
                            }
                            $this->mAssertResults = array();
                            $produced = array();
                            try {
                                if ( isset( $annotations[ 'dataProvider' ] ) ) {
                                    foreach ( $dataProvided as $params ) {
                                        if ( is_scalar( $params ) ) {
                                            $params = array( $params );
                                        }
                                        $ret = call_user_func_array( array( $testcase, $methodname ), $params );
                                        if ( !empty( $ret ) ) {
                                            $produced[] = $ret;
                                        }
                                    }
                                }
                                else if ( isset( $annotations[ 'producer' ] ) ) {
                                    $producer = $annotations[ 'producer' ][ 0 ];
                                    w_assert( isset( $this->mProduced[ $producer ] ), 'Producer did not produce something' );
                                    foreach ( $this->mProduced[ $producer ] as $arg ) {
                                        $ret = call_user_func( array( $testcase, $methodname ), $arg );
                                        if ( !empty( $ret ) ) {
                                            $produced[] = $ret;
                                        }
                                    }
                                }
                                else {
                                    $ret = call_user_func( array( $testcase, $methodname ) ); // MAGIC
                                    if ( !empty( $ret ) ) {
                                        $produced = array( $ret );
                                    }
                                }
                            }
                            catch ( Exception $e ) {
                                $this->Inform( New AssertResultFailedByException( $e->getMessage(), $e->getTrace() ), $methodname );
                                $runresults[] = New RunResult( $this->mAssertResults, $methodname );
                                // $water->ProfileEnd();
                                break;
                            }
                            $this->mProduced[ $methodname ] = $produced;
                            $runresults[] = New RunResult( $this->mAssertResults, $methodname );
                            // $water->ProfileEnd();
                        }
                    }
                }
                try {
                    $testcase->TearDown();
                }
                catch ( Exception $e ) {
                    $runresults[] = New RunResult( array( New AssertResultFailedByException( $e->getMessage(), $e->getTrace() ) ), '[TearDown]' );
                }
                $this->mTestResults[ $i ] = New TestcaseResult( $testcase, $runresults );
                // $water->ProfileEnd();
            }
            // $water->ProfileEnd();
        }
        public function GetResults() {
            return $this->mTestResults;
        }
        public function Inform( AssertResult $result ) {
            $this->mAssertResults[] = $result;
            return $result;
        }
        public function RequireFailed( AssertResult $result ) {
            throw New Exception( "Required assertion failed yielding to immediate TearDown: " . $result->Message );
        }
    }
    
    class TestcaseResult implements Iterator { // a group of run results, the results for a complete testcase
        protected $mRunResults;
        protected $mTestcase;
        protected $mSuccess;
        protected $mNumRuns;
        protected $mNumSuccessfulRuns;
        protected $mNumAssertions;
       
           public function __get( $key ) {
            switch ( $key ) {
                case 'Results':
                    return $this->mRunResults;
                case 'Testcase':
                case 'NumRuns':
                case 'NumSuccessfulRuns':
                case 'NumAssertions':
                case 'Success':
                    $attribute = 'm' . $key;
                    return $this->$attribute;
            }
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
        public function TestcaseResult( Testcase $testcase, array $runresults ) {
            w_assert( is_array( $runresults ) );
            $this->mNumRuns = count( $runresults );
            $this->mNumSuccessfulRuns = 0;
            $this->mNumAssertions = 0;
            $this->mSuccess = true;
            foreach ( $runresults as $runresult ) {
                w_assert( $runresult instanceof RunResult );
                if ( $runresult->Success ) {
                    ++$this->mNumSuccessfulRuns;
                }
                else {
                    $this->mSuccess = false;
                }
                $this->mNumAssertions += $runresult->NumAssertions;
            }
            $this->mTestcase = $testcase;
            $this->mRunResults = $runresults;
        }
    }
    
    class RunResult implements Iterator { // a group of assertion results, a result of a test (function in the testcase class)
        protected $mAssertionResults;
        protected $mSuccess;
        protected $mRunName;
        protected $mNumSuccessfulAssertions;
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
        public function __get( $key ) {
            switch ( $key ) {
                case 'RunName':
                case 'Success':
                case 'NumAssertions':
                case 'NumSuccessfulAssertions':
                    $attribute = 'm' . $key;
                    return $this->$attribute;
            }
        }
        public function RunResult( array $assertionresults, $runname ) {
            w_assert( is_string( $runname ) );
            w_assert( !empty( $runname ) );
            $this->mRunName = $runname;
            $this->mNumAssertions = count( $assertionresults );
            $this->mSuccess = true;
            $this->mNumSuccessfulAssertions = 0;
            foreach ( $assertionresults as $assertionresult ) {
                w_assert( $assertionresult instanceof AssertResult );
                if ( $assertionresult->Success ) {
                    ++$this->mNumSuccessfulAssertions;
                }
                else {
                    $this->mSuccess = false;
                }
            }
            $this->mAssertionResults = $assertionresults;
        }
    }
    
    class AssertResult { // most basic test, a simple assertion
        protected $mSuccess;
        protected $mMessage;
        protected $mActual;
        protected $mExpected;
        
        public function __get( $key ) {
            switch ( $key ) {
                case 'Message':
                case 'Success':
                case 'Actual':
                case 'Expected':
                    $attribute = 'm' . $key;
                    return $this->$attribute;
            }
        }
        public function __construct( $success, $message, $actual, $expected ) {
            $this->mSuccess  = $success;
            $this->mMessage  = $message;
            $this->mActual   = $actual;
            $this->mExpected = $expected;
        }
    }

    class AssertResultFailedByException extends AssertResult {
        protected $mCallstack;

        public function __get( $key ) {
            switch ( $key ) {
                case 'Callstack':
                    return $this->mCallstack;
                default:
                    return parent::__get( $key );
            }
        }
        public function AssertResultFailedByException( $message, $callstack ) {
            w_assert( is_array( $callstack ) );
            $this->mCallstack = $callstack;
            parent::__construct( false, $message, '(exceptional failure)', '' );
        }
    }
?>
