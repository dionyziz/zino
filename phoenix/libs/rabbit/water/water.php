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

    define( 'WATER_HTTPERROR_CURL', 418 );
    
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
            if ( array_keys( $what ) === range( 0, count( $what ) - 1 ) ) {
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
        protected $mNumErrors = 0;
        protected $mNumWarnings = 0;
        protected $mNumNotices = 0;
        protected $mNumTraces = 0;
        protected $mNumQueries = 0;
        protected $mFootprintURL = '';
        protected $mDataSent = false;
        protected $mResponseStatus = 0;

        public function Enable() {} // TODO...
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
            return;
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
            }
            $this->AppendAlert( $type, $errstr, time(), debug_backtrace() );
        }
        public function HandleException( Exception $e ) {
            return;
            $this->AppendAlert( WATER_ALERTTYPE_ERROR, $e->getMessage(), time(), $e->getTrace() );
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
            if ( count( $this->mFootprintData ) > 1 ) {
                $this->mFootprintData = substr( $this->mFootprintData, 0, -1 );
            }
            $this->mFootprintData .= ']';
        }
        public function Post() {
            $this->mDataSent = true;

            $this->Finalize();
            $curl = curl_init();

            die( $this->mFootprintData );

            $data = array(
                'protocolversion' => WATER_PROTOCOL_VERSION,
                'authentication' => $this->mProjectName . ':' . $this->mProjectKey,
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

            $this->DumpHTML();
        }
        protected function DumpHTML() {
            ?><style type="text/css">
                div#water, div#water h2, div#water ul, div#water li {
                    /* clear parent application stylesheets */
                    font-size: 10pt;
                    clear: none;
                    float: none;
                    background: inherit;
                    color: black;
                    text-align: left;
                    position: static;
                    padding: 0;
                    margin: 0;
                    font-weight: normal;
                    border-collapse: collapse;
                    border-spacing: 0;
                    border: none;
                    text-decoration: none;
                    cursor: pointer;
                    z-index: 99999999999; /* a wild maximum guess */
                }
                div#water {
                    font-family: Verdana;
                    border: 1px solid #abc;
                    width: 200px;
                    background-color: #333;
                    opacity: 0.9;
                    position: fixed;
                    bottom: 10px;
                    right: 10px;
                    cursor: pointer;
                }
                div#water h2 {
                    margin: 0;
                    padding: 4px;
                    background-color: #3a5d89;
                    color: #eee;
                    border-bottom: #5a8da9;
                }
                div#water ul {
                    padding: 5px;
                    margin: 0;
                    list-style: none;
                }
                div#water ul li {
                    color: white;
                }
                div#water p {
                    color: white;
                    padding: 5px;
                }
            </style>
            <div id="water" onclick="window.open('<?php
                if ( $this->mResponseStatus == 200 ) {
                    echo htmlspecialchars( $this->mFootprintURL );
                }
                else {
                    ?>https://water.kamibu.com/errors/<?php
                    echo $this->mResponseStatus;
                }
                ?>')" title="Debug this page"><?php
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

    $water = New Water();

    set_error_handler( array( $water, 'HandleError' ) );
    set_exception_handler( array( $water, 'HandleException' ) );

    return $water;
?>
