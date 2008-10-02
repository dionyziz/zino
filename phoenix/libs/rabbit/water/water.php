<?php
    error_reporting( E_ALL );

    define( 'WATER_PROTOCOL_VERSION', 1 );
    define( 'WATER_EVENTTYPE_ALERT', 1 );
    define( 'WATER_EVENTTYPE_PROFILE', 2 );
    define( 'WATER_EVENTTYPE_QUERY', 3 );
    
    function w_assert( $condition, $description = false ) {
        assert( $condition );
    }

    class Water {
        protected $mProjectName = 'test';
        protected $mProjectKey = 'thebigtest';
        protected $mFootprintData = '[';

        public function Enable() {}
        public function Disable() {}
        public function Enabled() {}
        public function Trace() {}
        public function Notice() {}
        public function Warning() {}
        public function Profile() {}
        public function ProfileEnd() {}
        public function LogSQL() {}
        public function LogSQLEnd() {}
        public function __destruct() {
            die( 'Another day.' );
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
                    $item[ 'file' ],
                    $item[ 'line' ]
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
        }
    }

    global $water;

    $water = New Water();

    return $water;
?>
