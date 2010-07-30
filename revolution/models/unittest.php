<?php
    /*
        Developed by dionyziz (phoenix)
        Extended by abresas (revolution)
    */

    abstract class ModelTestcase extends Testcase {
        protected $mCovers;
        protected $mTable;
        protected $mTablePrefix;
        protected $mForeignKey = 'id';
        
        protected function GenerateTestUsers( $num ) {
            db( "DELETE FROM `users` WHERE `user_name` LIKE 'DELETEMEtestuser%' LIMIT 5;" );
            for ( $i = 0; $i < $num; ++$i ) {
                $name = "DELETEMEtestuser" . $i;
                $email = "DELETEMEtestuser" . $i . "@zino.gr";
                $userid = User::Create( $name, $email, "password" );
                $this->mTestUsers[] = array( 'id' => $userid, 'name' => $name, 'email' => $email );
            }
            return $this->mTestUsers;
        }
        protected function DeleteTestUsers() {
            foreach ( $this->mTestUsers as $user ) {
                User::Delete( (int)$user[ 'id' ] );
            }
        }
        public function UserProvider() {
            return $this->mTestUsers;
        }
        public function GetTestUsers() {
            return $this->mTestUsers;
        }
        public function GetRandomTestUser() {
            $count = count( $this->mTestUsers );
            $r = rand( 0, $count - 1 );
            return $this->mTestUsers[ $r ];
        }
        protected function RandomValues( $data, $num ) {
            $keys = array_rand( $data, $num );
            $ret = array();
            foreach ( $keys as $key ) {
                $ret[] = $data[ $key ];
            }
            return $ret;
        }
        public function ValidIds( $num = 3 ) {
            $primaryfield = $this->mTablePrefix . $this->mForeignKey;
            $res = db( "SELECT $primaryfield FROM `" . $this->mTable . "` ORDER BY RAND() LIMIT $num;" );
            $ret = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $ret[] = (int)$row[ 0 ];
            }
            return $ret;
        }
    }
    
    abstract class Testcase {
        protected $mTester;
        protected $mName;
        protected $mAppliesTo;
        protected $mOnPreConditions;
        protected $mCalled = false;
        protected $mCovers = '';
        protected $mTestUsers = array();
        
        public function GetCoveredClass() {
            return $this->mCovers;
        }
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
        public function Called( $func ) {
            $this->mCalled = $func;
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
            $this->AssertEquals( 'array', gettype( $array ), $message );
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
                $message = $this->mCalled . " returned array with wrong value for $key.";
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

    function Test_GetTestcasesByName() {
        $validTests = Test_GetTestcases();
        $validTestsByName = array();
        foreach ( $validTests as $testcase ) {
            $validTestsByName[ $testcase->Name ] = $testcase;
        }
        return $validTestsByName;
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
        protected $mPreviousMethodname = '';
        protected $mProduced = array();

        public function Tester() {
            $this->mTestcases = array();
        }
        public function AddTestcase( Testcase $testcase ) {
            $this->mTestcases[] = $testcase;
        }
        public function RunSetUp( $testcase, &$runresults ) {
            try {
                $testcase->SetUp();
                return true;
            }
            catch ( Exception $e ) {
                $runresults[] = New RunResult( array( New AssertResultFailedByException( $e->getMessage(), $e->getTrace() ) ), '[SetUp]' );
                return false;
            }
        }
        public function RunPreConditions( $testcase, &$runresults ) {
            try {
                $testcase->StartPreConditions();
                return true;
            }
            catch ( Exception $e ) {
                $runresults[] = New RunResult( array( New AssertResultFailedByException( $e->getMessage(), $e->getTrace() ) ), '[PreConditions]' );
                return false;
            }
        }
        public function Run() {
            global $water;
            
            $this->mTestcaseResults = array();
            foreach ( $this->mTestcases as $i => $testcase ) {
                $runresults = $this->RunTestcase( $testcase );
                $this->mTestResults[ $i ] = New TestcaseResult( $testcase, $runresults );
            }
        }
        public function RunTestcase( $testcase ) {
            $this->mPreviousMethodName = '';
            $this->mProduced = array();

            $testcase->Tester = $this; // allows testcase to report results back to this tester
            $obj = New ReflectionObject( $testcase );
            $methods = $obj->getMethods();
            $runresults = array();
            $this->mAssertResults = array();
            //$goodtogo = $this->RunSetUp( $testcase, $runresults );
            $testcase->SetUp();
            $goodtogo = $this->RunPreConditions( $testcase, $runresults );
            if ( !$goodtogo ) {
                // $this->RunTearDown();
                $testcase->TearDown();
                return array();
            }
            foreach ( $methods as $method ) {
                if ( $this->ValidMethod( $method ) ) {
                    $methodname = $method->getName();
                    $this->mAssertResults = array();
                    $run = new TestRun( $this, $testcase, $method );
                    $run->Init();
                    $runresults[] = New RunResult( $this->mAssertResults, $methodname );
                    $this->mPreviousMethodName = $methodname;
                }
            }
            $testcase->TearDown();
            return $runresults;
        }
        public function ValidMethod( $method ) {
            $methodname = $method->getName();
            return ( $method->isPublic() && substr( $methodname, 0, strlen( 'Test' ) ) == 'Test' && $methodname != 'Testcase' );
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
        public function HandleRunException( Exception $e, $methodname ) {
            $this->Inform( New AssertResultFailedByException( $e->getMessage(), $e->getTrace() ), $methodname );
            return New RunResult( $this->mAssertResults, $methodname );
        }
        public function AddProduced( $producer, $stuff ) {
            if ( !isset( $this->mProduced[ $producer ] ) ) {
                $this->mProduced[ $producer ] = array();
            }
            $this->mProduced[ $producer ][] = array( $stuff );
        }
        public function GetProduced( $producer ) {
            return $this->mProduced[ $producer ];
        }
        public function GetPreviouslyProduced() {
            if ( isset( $this->mProduced[ $this->mPreviousMethodName ] ) ) {
                return $this->mProduced[ $this->mPreviousMethodName ];
            }
            return array();
        }
    }


    // represents running one test (method) of a testcase
    class TestRun {
        protected $mTester;
        protected $mTestcase;
        protected $mMethod;
        protected $mCovers = false;

        public function TestRun( $tester, $testcase, $method ) {
            $this->mTester = $tester;
            $this->mTestcase = $testcase;
            $this->mMethod = $method;

            $partialname = substr( $this->mMethod->getName(), strlen( 'Test' ) );
            $class = $this->mTestcase->GetCoveredClass();
            if ( !empty( $class ) && method_exists( $class, $partialname ) ) {
                $this->mCovers = $this->mTestcase->GetCoveredClass() . "::" . $partialname;
            }
        }
        public function Init() {
            $allParams = $this->HandleAnnotations();
            $runresults = array();
            if ( empty( $allParams ) ) {
                return $this->CallMethod();
            }
            return $this->CallMethodRepeatedly( $allParams );
        }
        public function GetAnnotations() {
            $lines = explode( "\n", $this->mMethod->getDocComment() );
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
        public function HandleAnnotations() {
            $annotations = $this->GetAnnotations();
            if ( isset( $annotations[ 'covers' ] ) ) {
                $this->mCovers = $annotations[ 'covers' ][ 0 ];
            }
            $provided = $this->GetProviderParams( $annotations );
            $produced = $this->GetProducerParams( $annotations );
            $allParams = array();
            if ( !empty( $provided ) && !empty( $produced ) ) {
                // w_assert( count( $provided ) == count( $produced ), 'produced and provided must have same count' );
                if ( count( $provided ) != count( $produced ) ) {
                    return $provided;
                }
                foreach ( $provided as $i => $params ) {
                    $provided[ $i ] = array_merge( $provided[ $i ], $produced[ $i ] );
                }
                return $provided;
            }
            else if ( !empty( $provided ) ) {
                return $provided;
            }
            return $produced;
        }
        public function GetProviderParams( $annotations ) {
            if ( !isset( $annotations[ 'dataProvider' ] ) ) {
                return array();
            }
            $provider = $annotations[ 'dataProvider' ][ 0 ];
            return call_user_func( array( $this->mTestcase, $provider ) );
        }
        public function GetProducerParams( $annotations ) {
            if ( isset( $annotations[ 'producer' ] ) ) {
                return $this->mTester->GetProduced( $annotations[ 'producer' ][ 0 ] );
            }
            return $this->mTester->GetPreviouslyProduced(); // if any
        }
        public function CallMethodRepeatedly( $allParams ) {
            $runresults = array();
            foreach ( $allParams as $params ) {
                $runresults[] = $this->CallMethod( $params );
            }
            return $runresults;
        }
        public function CallMethod( $params = array() ) {
            $methodname = $this->mMethod->getName();
            // echo "calling $methodname with params: ";
            // var_dump( $params );

            $this->mTestcase->Called( $this->mCovers );
            $runresults = array();
            try {
                $ret = call_user_func_array( array( $this->mTestcase, $methodname ), $params );
            }
            catch ( Exception $e ) {
                $runresults[] = $this->mTester->HandleRunException( $e, $methodname );
            }
            if ( !empty( $ret ) ) {
                $this->mTester->AddProduced( $methodname, $ret );
                // $this->mProduced[ $methodname ][] = $ret;
            }
            return $runresults;
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
