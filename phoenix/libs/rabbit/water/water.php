<?php
    if ( !defined( 'WATER_ENABLE' ) ) {
        define( 'WATER_ENABLE', true );
    }

    error_reporting( E_ALL );

    define( 'WATER_PROTOCOL_VERSION', 1 );

    define( 'WATER_EVENTTYPE_ALERT', 1 );
    define( 'WATER_EVENTTYPE_PROFILE', 2 );
    define( 'WATER_EVENTTYPE_QUERY', 3 );

    define( 'WATER_ALERTTYPE_TRACE', 0 );
    define( 'WATER_ALERTTYPE_NOTICE', 1 );
    define( 'WATER_ALERTTYPE_WARNING', 2 );
    define( 'WATER_ALERTTYPE_ERROR', 3 );

    define( 'WATER_HTTPERROR_CURL', 418 );

    define( 'WATER_E_USER_TRACE', 0 );
    
    if ( !defined( 'E_RECOVERABLE_ERROR' ) ) {
        define( 'E_RECOVERABLE_ERROR', 4096 );
    }
    if ( !defined( 'E_DEPRECATED' ) ) {
        define( 'E_DEPRECATED', 8192 );
    }
    if ( !defined( 'E_USER_DEPRECATED' ) ) {
        define( 'E_USER_DEPRECATED', 16384 );
    }
    
    function w_assert( $condition, $description = false, $callstack = false ) {
        global $water;

        if ( !$condition ) {
            echo 'Assertion failed: ' . $description . "\n";
            if ( $callstack === false ) {
                $callstack = debug_backtrace();
            }
            $trace = $water->FormatCallstack( $callstack );
            ob_start();
            print_r( $trace );
            echo nl2br( ob_get_clean() );
            die();
        }
    }

    function w_json_encode( $what, $chopstrings = -1, $depth = 0, $ascii = true ) {
        if ( $depth > 6 ) {
            return '"[and more]"';
        }
        if ( is_int( $what ) || is_float( $what ) ) {
            return $what;
        }
        if ( is_bool( $what ) ) {
            return $what? 'true': 'false';
        }
        if ( is_string( $what ) ) {
            if ( $chopstrings > 0 && $chopstrings < strlen( $what ) ) { 
                $what = substr( $what, 0, $chopstrings ) . '...';
            }
            if ( $ascii ) {
                return '"' . addcslashes( $what, "\\\"\n\r\t\0..\37" ) . '"';
            }
            return '"' . addcslashes( $what, "\\\"\n\r\t\0..\37!@\@\177..\377" ) . '"';
        }
        if ( is_resource( $what ) ) {
            return '"[resource: ' . get_resource_type( $what ) . ']"';
        }
        if ( is_null( $what ) ) {
            return 'null';
        }
        if ( is_object( $what ) ) {
            return '"[object: ' . get_class( $what ) . ']"';
        }
        if ( is_array( $what ) ) {
            $ret = '';
            // check if it is non-assosiative
            if ( empty( $what ) || array_keys( $what ) === range( 0, count( $what ) - 1 ) ) {
                for ( $i = 0; $i < count( $what ); ++$i ) {
                    $ret .= w_json_encode( $what[ $i ], $chopstrings, $depth + 1, $ascii );
                    if ( $i + 1 < count( $what ) ) {
                        $ret .= ',';
                    }
                }
                return '[' . $ret . ']';
            }
            reset( $what );
            for ( $i = 0 ; $i < count( $what ) ; ++$i ) {
                $item = each( $what );
                $ret .= w_json_encode( $item[ 'key' ], $chopstrings, $depth, $ascii )
                     . ':'
                     . w_json_encode( $item[ 'value'], $chopstrings, $depth + 1, $ascii );
                if ( $i + 1 < count( $what ) ) {
                    $ret .= ',';
                }
            }
            return '{' . $ret . '}';
        }
        return '"[unknown]"';
    }

    interface WaterBase {
        public function SetPageURL( $url );
        public function Trace( $description, $dump = false );
        public function Notice( $description, $dump = false );
        public function Warning( $description, $dump = false );
        public function Profile( $description, $dump = false );
        public function ProfileEnd();
        public function LogSQL( $description );
        public function LogSQLEnd();
        public function HandleError( $errno, $errstr );
        public function ProcessError( $errno, $errstr, $backtrace );
        public function HandleException( Exception $e );
        public function AppendAlert( $type, $description, $start, $callstack );
        public function AppendProfile( $description, $start, $end, $callstack );
        public function AppendQuery( $description, $start, $end, $callstack );
        public function Post();
        public function FormatCallstack( $callstack );
        public function ExitWithoutSubmission();
    }

    class WaterDummy implements WaterBase {
        public function SetPageURL( $url ) {}
        public function Trace( $description, $dump = false ) {}
        public function Notice( $description, $dump = false ) {}
        public function Warning( $description, $dump = false ) {}
        public function Profile( $description, $dump = false ) {}
        public function ProfileEnd() {}
        public function LogSQL( $description ) {}
        public function LogSQLEnd() {}
        public function HandleError( $errno, $errstr ) {}
        public function ProcessError( $errno, $errstr, $backtrace ) {}
        public function HandleException( Exception $e ) {}
        public function AppendAlert( $type, $description, $start, $callstack ) {}
        public function AppendProfile( $description, $start, $end, $callstack ) {}
        public function AppendQuery( $description, $start, $end, $callstack ) {}
        public function Post() {}
        public function FormatCallstack( $callstack ) {}
        public function ExitWithoutSubmission() {}
    }

    class Water implements WaterBase {
        protected $mProjectName = 'zino';
        protected $mProjectKey = 'flowing314sand';
        protected $mFootprintData = '[';
        protected $mNumErrors = 0;
        protected $mNumWarnings = 0;
        protected $mNumNotices = 0;
        protected $mNumTraces = 0;
        protected $mNumQueries = 0;
        protected $mFootprintURL = '';
        protected $mDataSent = false;
        protected $mResponseStatus = 0;
        protected $mLastSQLQuery = '';
        protected $mLastSQLQueryStart = false;
        protected $mProfiles = array();
        protected $mPageURL = '';

        public function __construct() {
            $this->mPageURL = $_SERVER[ 'PHP_SELF' ];
        }
        public function SetPageURL( $url ) {
            $this->mPageURL = $url;
        }
        public function Trace( $description, $dump = false ) {
            $this->ProcessError( WATER_E_USER_TRACE, $description, debug_backtrace() );
        }
        public function Notice( $description, $dump = false ) {
            $this->ProcessError( E_USER_NOTICE, $description, debug_backtrace() );
        }
        public function Warning( $description, $dump = false ) {
            $this->ProcessError( E_USER_WARNING, $description, debug_backtrace() );
        }
        public function Profile( $description, $dump = false ) {
            array_push( $this->mProfiles, array( $description, microtime( true ) ) );
        }
        public function ProfileEnd() {
            if ( empty( $this->mProfiles ) ) {
                $this->Warning( 'Water could not complete the profile you requested; make sure you run $water->Profile() when starting and $water->ProfileEnd() when completing your benchmark' );
                return;
            }
            $profile = array_pop( $this->mProfiles );
            $this->AppendProfile( $profile[ 0 ], $profile[ 1 ], microtime( true ), debug_backtrace() );
        }
        public function LogSQL( $description ) {
            if ( $this->mLastSQLQueryStart !== false ) {
                $this->Warning( 'Water could not log SQL query "'
                    . $description
                    . '" -- we are currently logging another SQL query ("'
                    . $this->mLastSQLQuery
                    . '"); use $water->LogSQLEnd() to terminate logging the previous query once it is done.' );
                return;
            }
            $this->mLastSQLQueryStart = microtime( true );
            $this->mLastSQLQuery = $description;
        }
        public function LogSQLEnd() {
            $this->AppendQuery( $this->mLastSQLQuery, $this->mLastSQLQueryStart, microtime( true ), debug_backtrace() );
            $this->mLastSQLQueryStart = false;
        }
        public function HandleError( $errno, $errstr ) {
            $this->ProcessError( $errno, $errstr, debug_backtrace() );
        }
        public function ProcessError( $errno, $errstr, $backtrace ) {
            switch ( $errno ) {
                case E_ERROR:
                case E_CORE_ERROR:
                case E_PARSE:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                case E_RECOVERABLE_ERROR:
                    $type = WATER_ALERTTYPE_ERROR;
                    ++$this->mNumErrors;
                    break;
                case E_WARNING:
                    die( 'ERR: ' . $errstr );
                case E_CORE_WARNING:
                case E_COMPILE_WARNING:
                case E_USER_WARNING:
                    $type = WATER_ALERTTYPE_WARNING;
                    ++$this->mNumWarnings;
                    break;
                case E_NOTICE:
                case E_USER_NOTICE:
                case E_STRICT:
                case E_DEPRECATED:
                case E_USER_DEPRECATED:
                    $type = WATER_ALERTTYPE_NOTICE;
                    ++$this->mNumNotices;
                    break;
                case WATER_E_USER_TRACE:
                    $type = WATER_ALERTTYPE_TRACE;
                    ++$this->mNumTraces;
                    break;
            }
            $this->AppendAlert( $type, $errstr, microtime( true ), $backtrace );
        }
        public function HandleException( Exception $e ) {
            w_assert( false, $e->getMessage(), $e->getTrace() );
            // $this->AppendAlert( WATER_ALERTTYPE_ERROR, $e->getMessage(), microtime( true ), $e->getTrace() );
        }
        public function __destruct() {
            if ( !$this->mDataSent ) {
                $this->Post();
            }
        }
        public function AppendAlert( $type, $description, $start, $callstack ) {
            $this->mFootprintData .= w_json_encode( array(
                WATER_EVENTTYPE_ALERT,
                array( $type, $description, $start ),
                $this->FormatCallstack( $callstack )
            ) ) . ',';
        }
        public function AppendProfile( $description, $start, $end, $callstack ) {
            $this->mFootprintData .= w_json_encode( array(
                WATER_EVENTTYPE_PROFILE,
                array( $description, $start, $end ),
                $this->FormatCallstack( $callstack )
            ) ) . ',';
        }
        public function AppendQuery( $description, $start, $end, $callstack ) {
            $this->mFootprintData .= w_json_encode( array(
                WATER_EVENTTYPE_QUERY,
                array( $description, $start, $end ),
                $this->FormatCallstack( $callstack )
            ) ) . ',';
        }
        public function FormatCallstack( $callstack ) {
            $ret = array();
            foreach ( $callstack as $item ) {
                $func = '';
                if ( isset( $item[ 'type' ] ) ) {
                    switch ( $item[ 'type' ] ) {
                        case '->':
                            $func = $item[ 'class' ] . '->';
                            break;
                        case '::':
                            $func = $item[ 'class' ] . '::';
                            break;
                    }
                }
                $func .= $item[ 'function' ];
                $ret[] = array(
                    $func,
                    isset( $item[ 'file' ] )? $item[ 'file' ]: '',
                    isset( $item[ 'line' ] )? $item[ 'line' ]: 0
                );
            }
            return $ret;
        }
        protected function Finalize() {
            if ( strlen( $this->mFootprintData ) > 1 ) {
                $this->mFootprintData = substr( $this->mFootprintData, 0, -1 );
            }
            $this->mFootprintData .= ']';
        }
        protected function FindContentType() {
            $headers = headers_list();
            foreach ( $headers as $header ) {
                $split = explode( ':', $header, 2 );
                if ( count( $split ) > 1 ) {
                    $key = trim( $split[ 0 ] );
                    $value = trim( $split[ 1 ] );
                    if ( strtolower( $key ) == 'content-type' ) {
                        $valueparts = explode( ';', $value, 2 );
                        $value = trim( $valueparts[ 0 ] );
                        switch ( strtolower( $value ) ) {
                            case 'text/html':
                            case 'application/xhtml+xml':
                                return 'HTML';
                            default:
                                return false;
                        }
                    }
                }
            }
        }
        public function ExitWithoutSubmission() {
            $this->mDataSent = true;
        }
        public function Post() {
            $this->mDataSent = true;

            $this->Finalize();
            $curl = curl_init();


            $data = array(
                'protocolversion' => WATER_PROTOCOL_VERSION,
                'authentication' => $this->mProjectName . ':' . $this->mProjectKey,
                'url' => $this->mPageURL,
                'footprintdata' => $this->mFootprintData,
            );
            
            $header[ 0 ] = "Accept: text/plain";
            $header[] = "Accept-Language: en-us,en;q=0.5";
            $header[] = "Accept-Encoding: ";
            $header[] = "Accept-Charset: utf-8";
            $header[] = "Keep-Alive: 300";
            $header[] = "Connection: keep-alive";
            $header[] = "Expect: ";

            $server = 'https://water.kamibu.com/do/footprint/new';
            curl_setopt( $curl, CURLOPT_URL, $server );
            curl_setopt( $curl, CURLOPT_USERAGENT, "Water-PHP/5.0 (water.kamibu.com)" );
            curl_setopt( $curl, CURLOPT_HTTPHEADER, $header );
            curl_setopt( $curl, CURLOPT_ENCODING, '' );
            curl_setopt( $curl, CURLOPT_AUTOREFERER, true );
            curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $curl, CURLOPT_POST, 1 );
            curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
            curl_setopt( $curl, CURLOPT_VERBOSE, 1 );
            curl_setopt( $curl, CURLOPT_CAINFO, 'libs/rabbit/water/orion.kamibu.com.crt' );
            curl_setopt( $curl, CURLOPT_CAPATH, 'libs/rabbit/water/orion.kamibu.com.crt' );
            curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 0 );
            $data = curl_exec( $curl );

            if ( $data === false ) {
                $this->mResponseStatus = WATER_HTTPERROR_CURL;
                curl_error( $curl );
            }
            else {
                $this->mResponseStatus = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
                $this->mFootprintURL = $data;
                curl_close( $curl );
            }

            switch ( $this->FindContentType() ) {
                case 'HTML':
                    $this->DumpHTML();
                    return;
            }
        }
        protected function DumpHTML() {
           ?><link href="https://water.kamibu.com/css/client.css" rel="stylesheet" type="text/css"></link>
            <script type="text/javascript" src="https://water.kamibu.com/js/client.js"></script>
            <div id="water" onclick="window.open('<?php
                if ( $this->mResponseStatus == 200 ) {
                    echo htmlspecialchars( $this->mFootprintURL );
                }
                else {
                    ?>https://water.kamibu.com/errors/<?php
                    echo $this->mResponseStatus;
                }
                ?>');" title="Debug this page"><?php
                if ( $this->mResponseStatus == 200 ) {
                    ?><h2>Debug this page</h2>
                        <ul>
                            <li><?php
                            echo $this->mNumWarnings;
                            ?> warnings</li>
                            <li><?php
                            echo $this->mNumNotices;
                            ?> notices</li>
                            <li><?php
                            echo $this->mNumTraces;
                            ?> traces</li>
                        </ul><?php
                }
                else {
                    ?><h2>Debugger error</h2>
                        <p>Your debug session could not be started.<br />
                        Click here to fix this problem.</p>
                    <?php
                }
            ?></div><?php
        }
    }

    global $water;

    if ( WATER_ENABLE ) {
        $water = New Water();
    }
    else {
        $water = New WaterDummy();
    }
    set_error_handler( array( $water, 'HandleError' ), 
                          E_WARNING | E_NOTICE 
                        | E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE 
                        | E_STRICT | E_RECOVERABLE_ERROR 
                        | E_DEPRECATED | E_USER_DEPRECATED 
                      );
    set_exception_handler( array( $water, 'HandleException' ) );

    return $water;
?>
