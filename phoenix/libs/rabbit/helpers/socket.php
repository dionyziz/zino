<?php
    /*
        Developer: Dionyziz
    */

    class SocketException extends Exception {
    }
    
    class SocketClient {
        protected $mConnection;
        protected $mConnected = false;
        protected $mAddress;
        protected $mPort;
        protected $mTimeout = 5; // in seconds
        
        public function WriteLine( $line ) {
            $this->Write( $line . "\n" );
        }
        public function Write( $data ) {
            $this->Connect();
            socket_write( $this->mConnection, $data );
        }
        public function ReadLine() {
            return socket_read( $this->mConnection, 4096, PHP_NORMAL_READ );
        }
        protected function Connect() {
            if ( $this->mConnected ) {
                return;
            }
            
            $this->mConnection = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
            
            if ( !socket_set_nonblock( $socket ) ) {
                throw New SocketException( 'Unable to set nonblock on socket' );
            }
            
            $time = time();
            while ( !@socket_connect( $this->mConnection, $this->mAddress, $this->mPort ) ) {
                $err = socket_last_error( $this->mConnection );
                if ( $err == 115 || $err == 114 ) { // 115 = EINPROGRESS; 114 = EALREADY
                    if ( ( time() - $time ) >= $timeout ) {
                        socket_close( $this->mConnection );
                        throw New SocketException( 'Connection timed out.' );
                    }
                    sleep( 1 );
                    continue;
                }
                throw New SocketException( 'A TCP/IP error has occurred: ' . socket_strerror( $err ) );
            }
            
            socket_set_block( $this->mConnection );
            
            $this->mConnected = true;
            
            $this->OnConnect();
        }
        protected function OnConnect() { // override me
        }
        public function __construct( $address, $port ) {
            w_assert( is_string( $address ) );
            w_assert( is_int( $port ) );
            w_assert( $port > 0 );
            w_assert( $port < 65536 );
            
            $this->mAddress = $address;
            $this->mPort = $port;
        }
        public function __destruct() {
            if ( $this->mConnected ) {
                socket_close( $this->mConnection );
            }
        }
    }
    
?>
