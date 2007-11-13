<?php
    /*
        Callisto:
            an orbited-based  (http://orbited.org/)
            AJAX comet        (http://en.wikipedia.org/wiki/Comet_%28programming%29)
            PHP
            publish/subscribe (http://en.wikipedia.org/wiki/Publish/subscribe)
            infrastructure.
        Developer: Dionyziz
    */
    
    global $libs;
    global $callipso_orbited; // ORBIT client to orbited
    
    $libs->Load( 'callisto/orbited' );
    
    $callipso_orbited = New OrbitedClient( 'localhost', 9000 );
    
    final class Callisto_Subscription extends Satori {
        protected $mId;
        protected $mChannel;
        protected $mLastActive;
        protected $mToken;
        
        protected function SetChannel( Callipso_Channel $channel ) {
            $this->mChannel = ( string )$channel;
        }
        protected function LoadDefaults() {
            $this->ScrambleToken();
        }
        public function RenewLease() {
            $this->mDb->Query(
                'UPDATE
                    `' . $this->mDbTable . '`
                SET
                    `subscription_lastactive` = NOW()
                WHERE
                    ' . $this->WhereAmI() . '
                LIMIT 1'
            );
        }
        public function Callisto_Subscription( $construct ) {
            global $db;
            global $callisto_subscriptions;
            
            $this->mDb = $db;
            $this->mDbTable = $callisto_subscriptions;
            $this->SetFields( array(
                'subscription_id'         => 'Id',
                'subscription_channel'    => 'Channel',
                'subscription_lastactive' => 'LastActive',
                'subscription_token'      => 'Token'
            ) );
            $this->MakeReadOnly( 'LastActive' );
            $this->MakeReadOnly( 'Token' );
            $this->Satori( $construct );
        }
        public function __toString() {
            return $this->mId . ', ' . $this->mToken . ', ' . $this->mChannel;
        }
        public function ScrambleToken() {
            $bytes = array(); // the array of all our 16 bytes
            for ( $i = 0; $i < 8 ; ++$i ) {
                $bytesequence = rand( 0, 65535 ); // generate a 2-bytes sequence
                $a = $bytesequence & 255; // a will be 0...255
                $b = $bytesequence >> 8; // b will also be 0...255
                $bytes[] = $a;
                $bytes[] = $b;
            }
            $token = ''; // start with an empty string
            foreach ( $bytes as $byte ) {
                $first = $byte & 15; // this will be 0...15
                $second = $byte >> 4; // this will be 0...15 again
                $token .= dechex($first) . dechex($second);
            }
            $this->mToken = $token; // 32 characters (16 bytes)
        }
    }
    
    final class Callisto_Channel {
        private $mRI; // Resource Identifier
        
        public function Callisto_Channel( $ri ) {
            w_assert( !empty( $ri ) );
            w_assert( is_string( $ri ) );
            if ( !preg_match( '#^[A-Za-z0-9_\-/\.\?\!]*$#', $ri ) ) { // whitelist RI
                throw New WaterException( 'Channel RI does not match whitelist' );
            }
            
            $this->mRI = $ri;
        }
        public function __toString() {
            return $this->mRI;
        }
        public function Subscribe() {
            $subscription = New Callisto_Subscription();
            $subscription->Channel = $this;
            $subscription->Save();
            
            return $subscription;
        }
        public function Publish( $message ) {
            global $db;
            global $callisto_subscriptions;
            global $callisto_orbited;
            
            $channel = addslashes( $this->mRI ); // we have whitelisted it, but just in case
            $res = $db->Query(
                "SELECT
                    *
                FROM
                    `$callisto_subscriptions`
                WHERE
                    `subscription_channel` = '$channel'"
            );
            
            $subscribers = array();
            
            while ( $row = $res->FetchArray() ) {
                $subscriber = New Callisto_Subscription( $row );
                $subscribers[] = ( string )$subscriber;
            }
            
            if ( empty( $subscribers ) ){
                $callipso_orbited->event( $subscribers, $message );
            }
        }
    }
?>
