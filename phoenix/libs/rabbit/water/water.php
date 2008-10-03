<?php
    error_reporting( E_ALL );

    define( 'WATER_PROTOCOL_VERSION', 1 );

    define( 'WATER_EVENTTYPE_ALERT', 1 );
    define( 'WATER_EVENTTYPE_PROFILE', 2 );
    define( 'WATER_EVENTTYPE_QUERY', 3 );

    define( 'WATER_ALERTTYPE_TRACE', 0 );
    define( 'WATER_ALERTTYPE_NOTICE', 1 );
    define( 'WATER_ALERTTYPE_WARNING', 2 );
    define( 'WATER_ALERTTYPE_ERROR', 3 );
    
    function w_assert( $condition, $description = false ) {
        assert( $condition );
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
                // avoid SELECT queries ( There is no point in hiding them ) -- Aleksis
                // NO. How do you know it's a query? The json encoding system should be generalized. 
                // Say I have a 50MB-string starting with the word "SELECT", this doesn't mean it has to crash water! --dionyziz
                $what = substr( $what, 0, $chopstrings ) . '...';
            }
            if ( $ascii ) {
                return '"' . addcslashes( $what, "\\\"\n\r\t\0..\37" ) . '"';
            }
            else {
                return '"' . addcslashes( $what, "\\\"\n\r\t\0..\37!@\@\177..\377" ) . '"';
            }
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
            // check if it is non-assosiative
            if ( array_keys( $what ) == range( 0, count( $what ) - 1 ) ) {
                $ret = '[';
                for ( $i = 0; $i < count( $what ); ++$i ) {
                    $ret .= w_json_encode( $what[ $i ], $chopstrings, $depth + 1, $ascii );
                    if ( $i + 1 < count( $what ) ) {
                        $ret .= ',';
                    }
                }
                $ret .= ']';
                return $ret;
            }
            $ret = '{';
            reset( $what );
            for ( $i = 0 ; $i < count( $what ) ; ++$i ) {
                $item = each( $what );
                $ret .= w_json_encode( $item[ 'key' ], $chopstrings, $depth, $ascii );
                $ret .= ':';
                $ret .= w_json_encode( $item[ 'value'], $chopstrings, $depth + 1, $ascii );
                if ( $i + 1 < count( $what ) ) {
                    $ret .= ',';
                }
            }
            $ret .= '}';
            return $ret;
        }
        return '"[unknown]"';
    }

    class Water {
        protected $mProjectName = 'test';
        protected $mProjectKey = 'thebigtest';
        protected $mFootprintData = '[';

        public function Enable() {} // tODO...
        public function Disable() {}
        public function Enabled() {}
        public function Trace() {}
        public function Notice() {}
        public function Warning() {}
        public function Profile() {}
        public function ProfileEnd() {}
        public function LogSQL() {}
        public function LogSQLEnd() {}
        public function HandleError( $errno, $errstr ) {
            switch ( $errno ) {
                case E_ERROR:
                case E_CORE_ERROR:
                case E_PARSE:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                case E_RECOVERABLE_ERROR:
                    $type = WATER_ALERTTYPE_ERROR;
                    break;
                case E_WARNING:
                case E_CORE_WARNING:
                case E_COMPILE_WARNING:
                case E_USER_WARNING:
                    $type = WATER_ALERTTYPE_WARNING;
                    break;
                case E_NOTICE:
                case E_USER_NOTICE:
                case E_STRICT:
                case E_DEPRECATED:
                case E_USER_DEPRECATED:
                    $type = WATER_ALERTTYPE_NOTICE;
                    break;
            }
            $this->AppendAlert( $type, $errstr, time(), debug_backtrace() );
        }
        public function HandleException( Exception $e ) {
            echo "WATER EXCEPTION: " . $e->getMessage() . "<br />";
        }
        public function __destruct() {
            $this->Post();
        }
        public function AppendAlert( $type, $description, $start, $callstack ) {
            $this->mFootprintData .= w_json_encode( array(
                WATER_EVENTTYPE_ALERT,
                array( $type, $description, $start ),
                $this->FormatCallstack( $callstack )
            ) );
        }
        public function AppendProfile( $description, $start, $end, $callstack ) {
            $this->mFootprintData .= w_json_encode( array(
                WATER_EVENTTYPE_PROFILE,
                array( $description, $start, $end ),
                $this->FormatCallstack( $callstack )
            ) );
        }
        public function AppendQuery( $description, $start, $end, $callstack ) {
            $this->mFootprintData .= w_json_encode( array(
                WATER_EVENTTYPE_QUERY,
                array( $description, $start, $end ),
                $this->FormatCallstack( $callstack )
            ) );
        }
        protected function FormatCallstack( $callstack ) {
            $ret = array();
            foreach ( $callstack as $item ) {
                $retitem = array(
                    $item[ 'function' ],
                    isset( $item[ 'file' ] )? $item[ 'file' ]: '',
                    isset( $item[ 'line' ] )? $item[ 'line' ]: 0
                );
                $ret[] = $item;
            }
            return $ret;
        }
        protected function Finalize() {
            $this->mFootprintData .= ']';
        }
        public function Post() {
            $this->Finalize();
            $curl = curl_init();

            $data = array(
                'protocolversion' => WATER_PROTOCOL_VERSION,
                'authentication' => $this->mProjectName . ':' . $this->mProjectKey,
                'footprintdata' => '[
                    [ 1, [ 2, "Testing", 1222902047 ], [ [ "ElementUserProfileView", "elements/user/profile/view.php", 29 ], [ "CommentCreate", "libs/comment.php", 713 ], [ "UserRegister", "libs/user.php", 40 ] ] ],
                    [ 2, [ "Test test!!", 1222222222, 1222222257 ] , [ [ 14, 15, 16 ], [ 28, 29, 30 ], [ 31, 32, 33 ]] ],
                    [ 3, [ "SELECT RAND();", 1222222222, 1222222361 ], [] ]
                ]',
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
            curl_setopt( $curl, CURLOPT_CAINFO, 'orion.kamibu.com.crt' );
            curl_setopt( $curl, CURLOPT_CAPATH, 'orion.kamibu.com.crt' );
            curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 0 );
            $data = curl_exec( $curl );

            if ( $data === false ) {
                ?>FAILURE!<?php
                echo curl_error( $curl );
                return;
            }
            
            $code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
            
            ?>SUCCESS! <?php
            echo $code;
            ?><br /><?php
            var_dump( $data );
            curl_close( $curl );
            die();
        }
    }

    global $water;

    $water = New Water();

    set_error_handler( array( $water, 'HandleError' ) );
    set_exception_handler( array( $water, 'HandleException' ) );

    return $water;
?>
