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
            return;

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
        private function get_all_functions() {
            static $memo;
            
            if ( !isset( $memo ) ) {
                $memo = get_defined_functions();
            }
            return $memo;
        }
        private function get_php_functions() {
            static $memo;
    
            if ( !isset( $memo ) ) {
                $allfunctions = $this->get_all_functions();
                $phpfunctions = $allfunctions['internal'];
                $phpfunctions[] = 'include';
                $phpfunctions[] = 'include_once';
                $phpfunctions[] = 'require';
                $phpfunctions[] = 'require_once';

                foreach ($phpfunctions as $function) {
                    $map[ $function ] = true;
                }
                $memo = $map;
            }
            
            return $memo;
        }
        private function callstack_plaintext( $callstack ) {
            $functions = $this->get_php_functions();
            
            $calltrace_depth = 0;
            $out = array();
            $maxfunction = 0;
            $maxsource   = 0;
            $maxline     = 0;
            for ( $i = count( $callstack ) - 1 ; $i >= 0 ; --$i ) {
                if ( isset( $callstack[ $i ] ) ) {
                    $info = $callstack[ $i ];
                    $file = $info[ 'file' ]; // should already have been chopped for us
                    if ( $file == '<water>' ) {
                        // avoid tracing water calls
                        continue;
                    }
                    ++$calltrace_depth;
                    $me[ 'function' ] = '';
                    echo "\n";
                    if ( isset( $info[ 'depth' ] ) ) {
                        $me[ 'function' ] .= str_repeat( ' ' , $info[ 'depth' ] * 2 );
                    }
                    if ( !empty( $info[ 'class' ] ) ) {
                        $me[ 'function' ] .= $info[ 'class' ];
                        if ( !isset( $call[ 'type' ] ) ) {
                            $call[ 'type' ] = '->';
                        }
                        switch ( $call[ 'type' ] ) {
                            case '::':
                                $me[ 'function' ] .= '::';
                                break;
                            case '->':
                            default:
                                $me[ 'function' ] .= '->';
                                break;
                        }
                    }
                    if ( isset( $info[ 'function' ] ) ) {
                        $phpfunction = isset( $functions[ $info[ 'function' ] ] );
                        if ( $phpfunction ) {
                            $me[ 'function' ] .= '*';
                        }
                        $me[ 'function' ] .= $info[ 'function' ];
                        if ( $phpfunction ) {
                            $me[ 'function' ] .= '*';
                        }
                    }
                    $me[ 'function' ] .= '(';
                    if ( isset( $info[ 'args' ] ) ) {
                        $j = 0;
                        $numargs = count( $info[ 'args' ] );
                        foreach ( $info[ 'args' ] as $arg ) {
                            $me[ 'function' ] .= ' ';
                            if ( is_object( $arg ) ) {
                                $me[ 'function' ] .= '[object]';
                            }
                            else if ( is_null( $arg ) ) {
                                $me[ 'function' ] .= '[null]';
                            }
                            else if ( is_resource( $arg ) ) {
                                $me[ 'function' ] .= '[resource: ' . get_resource_type( $arg ) . ']';
                            }
                            else if ( is_array( $arg ) ) {
                                $me[ 'function' ] .= '[array]';
                            }
                            else if ( is_scalar( $arg ) ) {
                                if ( is_bool( $arg ) ) {
                                    if ( $arg ) {
                                        $me[ 'function' ] .= '[true]';
                                    }
                                    else {
                                        $me[ 'function' ] .= '[false]';
                                    }
                                }
                                switch ( $info[ 'function' ] ) {
                                    case 'include':
                                    case 'include_once':
                                    case 'require':
                                    case 'require_once':
                                        $me[ 'function' ] .= $this->chopfile( $arg );
                                        break;
                                    default:
                                        if ( is_string( $arg ) ) {
                                            $me[ 'function' ] .= '"';
                                        }
                                        $argshow = str_replace( array( "\n", "\r" ), ' ', substr( $arg , 0 , 30 ) );
                                        $me[ 'function' ] .= $argshow;
                                        if ( strlen( $arg ) > strlen( $argshow ) ) {
                                            $me[ 'function' ] .= '...';
                                        }
                                        if ( is_string( $arg ) ) {
                                            $me[ 'function' ] .= '"';
                                        }
                                }
                            }
                            $me[ 'function' ] .= ' ';
                            ++$j;
                            if ( $j != $numargs ) {
                                $me[ 'function' ] .= ',';
                            }
                        }
                    }
                    $me[ 'function' ] .= ')';
                    $me[ 'source' ] = '';
                    if ( isset( $file ) ) {
                        if ( $calltrace_depth == 1 ) {
                            $me[ 'source' ] .= '*';
                        }
                        $me[ 'source' ] .= $file;
                        if ( $calltrace_depth == 1 ) {
                            $me[ 'source' ] .= '*';
                        }
                    }
                    if ( isset( $info[ 'line' ] ) ) {
                        $me[ 'line' ] = $info[ 'line' ];
                    }
                    else {
                        $me[ 'line' ] = '-';
                    }
                    if ( $maxsource < strlen( $me[ 'source' ] ) ) {
                        $maxsource = strlen( $me[ 'source' ] );
                    }
                    if ( $maxfunction < strlen( $me[ 'function' ] ) ) {
                        $maxfunction = strlen( $me[ 'function' ] );
                    }
                    if ( $maxline < strlen( $me[ 'line' ] ) ) {
                        $maxline = strlen( $me[ 'line' ] );
                    }
                    $out[] = $me;
                }
            }
            
            ?>function<?php
            echo str_repeat( ' ', $maxfunction - strlen( 'function' ) + 2 );
            ?>source<?php
            echo str_repeat( ' ', $maxsource - strlen( 'source' ) + 2 );
            ?>line<?php
            
            echo "\n";
            echo str_repeat( '-', $maxfunction + $maxsource + $maxline + 6 );
            echo "\n";
            foreach ( $out as $me ) {
                echo $me[ 'function' ];
                echo str_repeat( ' ', $maxfunction - strlen( $me[ 'function' ] ) + 2 );
                echo $me[ 'source' ];
                echo str_repeat( ' ', $maxsource - strlen( $me[ 'source' ] ) + 2 );
                echo $me[ 'line' ];
                echo "\n";
            }
            echo str_repeat( '-', $maxfunction + $maxsource + $maxline + 6 );
        }
        private function chopfile( $filename ) {
            if ( $filename === __FILE__ ) {
                return '<water>';
            }
            
            $beginpath = $this->mSettings[ 'server_root' ];
            
            if ( strtolower( substr( $filename, 0, strlen( $beginpath ) ) ) == $beginpath ) {
                $ret = substr( $filename, strlen( $beginpath ) );
            }
            else {
                $ret = $filename;
            }
            
            if ( strtolower( substr( $ret, -4 ) ) == '.php' ) {
                $ret = substr( $ret, 0, strlen( $ret ) - 4 );
            }
            
            return $ret;
        }
        public function callstack_html( $callstack ) {
            $functions = $this->get_php_functions();
    
            ?><div class="watertrace"><table class="callstack"><tr class="title">
            <td class="title">function</td><td class="title">source</td><td class="title">line</td></tr><?php
            $calltrace_depth = 0;
            for ( $i = count( $callstack ) - 1 ; $i >= 0 ; --$i ) {
                if ( isset( $callstack[ $i ] ) ) {
                    $info = $callstack[ $i ];
                    $file = $info[ 'file' ]; // should already have been chopped for us
                    if ( $file == '<water>' ) {
                        // avoid tracing water calls
                        continue;
                    }
                    ++$calltrace_depth;
                    ?><tr><?php
                    ?><td class="function"><?php
                    if ( isset( $info[ 'depth' ] ) ) {
                        echo str_repeat( '&nbsp;' , $info[ 'depth' ] * 2 );
                    }
                    if ( !empty( $info[ 'class' ] ) ) {
                        echo $info[ 'class' ];
                        if ( !isset( $call[ 'type' ] ) ) {
                            $call[ 'type' ] = '->';
                        }
                        switch ( $call[ 'type' ] ) {
                            case '::':
                                ?>::<?php
                                break;
                            case '->':
                            default:
                                ?>-&gt;<?php
                                break;
                        }
                    }
                    if ( isset( $info[ 'function' ] ) ) {
                        $phpfunction = isset( $functions[ $info[ 'function' ] ] );
                        if ( $phpfunction ) {
                            ?><a href="http://www.php.net/<?php
                            echo $info[ 'function' ];
                            ?>"><?php
                        }
                        echo $info[ 'function' ];
                        if ( $phpfunction ) {
                            ?></a><?php
                        }
                    }
                    ?>(<?php
                    if ( isset( $info[ 'args' ] ) ) {
                        $j = 0;
                        $numargs = count( $info[ 'args' ] );
                        foreach ( $info[ 'args' ] as $arg ) {
                            ?> <?php
                            if ( is_object( $arg ) ) {
                                ?>[<?php
                                echo get_class( $arg );
                                ?> object]<?php
                            }
                            else if ( is_null( $arg ) ) {
                                ?>[null]<?php
                            }
                            else if ( is_resource( $arg ) ) {
                                ?>[resource: <?php
                                echo get_resource_type( $arg );
                                ?>]<?php
                            }
                            else if ( is_array( $arg ) ) {
                                ?>[array]<?php
                            }
                            else if ( is_scalar( $arg ) ) {
                                if ( is_bool( $arg ) ) {
                                    if ( $arg ) {
                                        ?>[true]<?php
                                    }
                                    else {
                                        ?>[false]<?php
                                    }
                                }
                                switch ( $info[ 'function' ] ) {
                                    case 'include':
                                    case 'include_once':
                                    case 'require':
                                    case 'require_once':
                                        echo htmlspecialchars( $this->chopfile( $arg ) );
                                        break;
                                    default:
                                        if ( is_string( $arg ) ) {
                                            ?>"<?php
                                        }
                                        $argshow = substr( $arg , 0 , 30 );
                                        echo htmlspecialchars( $argshow );
                                        if ( strlen( $arg ) > strlen( $argshow ) ) {
                                            ?>...<?php
                                        }
                                        if ( is_string( $arg ) ) {
                                            ?>"<?php
                                        }
                                }
                            }
                            ?> <?php
                            ++$j;
                            if ( $j != $numargs ) {
                                ?>,<?php
                            }
                        }
                    }
                    ?>)</td><td class="file"><?php
                    if ( isset( $file ) ) {
                        if ( $calltrace_depth == 1 ) {
                            ?><b><?php
                        }
                        echo $file;
                        if ( $calltrace_depth == 1 ) {
                            ?></b><?php
                        }
                    }
                    ?></td><td class="line"><?php
                    if ( isset( $info[ 'line' ] ) ) {
                        echo $info[ 'line' ];
                    }
                    else {
                        ?>-<?php
                    }
                    ?></td></tr><?php
                }
            }
            ?></table></div><?php
        }
        public function callstack_lastword( $backtrace = false ) {
            if ( $backtrace === false ) {
                $backtrace = debug_backtrace();
            }
            
            $i = count( $backtrace ) - 1;
            foreach ( $backtrace as $call ) {
                if ( !isset( $call[ 'file' ] ) ) {
                    $call[ 'file' ] = '';
                }
                if ( strlen( $call[ 'file' ] ) < strlen( $this->mSettings[ 'server_root' ] ) ) {
                    $lastword[ $i ][ 'revision' ] = phpversion();
                    $lastword[ $i ][ 'file' ] = '(unknown)';
                    $lastword[ $i ][ 'line' ] = '-';
                }
                else {
                    $lastword[ $i ][ 'file' ] = $this->chopfile( $call[ 'file' ] );
                    $lastword[ $i ][ 'line' ] = $call[ 'line' ];
                }
                if ( isset( $call[ 'class' ] ) ) {
                    $lastword[ $i ][ 'class' ] = $call[ 'class' ];
                }
                $lastword[ $i ][ 'function' ] = $call[ 'function' ];
                $lastword[ $i ][ 'depth' ] = 0;
                if( isset($call[ 'args' ]) ) {
                    $lastword[ $i ][ 'args' ] = $call[ 'args' ];
                }
                if ( isset( $call[ 'type' ] ) ) {
                    $lastword[ $i ][ 'calltype' ] = $call[ 'type' ];
                }
                --$i;
            }
            
            return $lastword;
        }
        public function callstack( $callstack ) {
            global $page;
            
            if ( $page instanceof PageHTML ) {
                return $this->callstack_html( $callstack );
            }
            return $this->callstack_plaintext( $callstack );
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
