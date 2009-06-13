<?php

    /*
    SPOT - Social Prediction And Optimization Tool
    Class for connecting to the Spot deamon.

    Coming soon: unit tests!
    */

    define( 'SPOT_PORT', 21490 );


    class Spot {
        private static $mRequestHeader = "SPOT\n";
        private static $mServerIp = '88.198.246.217'; // europa.kamibu.com

        public function __construct() {
            // do nothing! static methods
        }
        private static function SendRequest( $requestBody ) {
            // TODO: Response Text
            // TODO: Error checking

            $request = self::$mRequestHeader . $requestBody;
            
            $sock = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
            w_assert( $sock !== false, "Socket creation failed. Reason: " . socket_strerror( socket_last_error() ) );
            $result = socket_connect( $sock, self::$mServerIp, SPOT_PORT );
            w_assert( $result !== false, "Socket connection failed. Reason: ($result) " . socket_strerror( socket_last_error( $socket ) ) );
            socket_write( $sock, $request );

            $response = socket_read( $socket, 1024 );
            socket_close( $sock );

            $lines = explode( "\n", $response );
            w_assert( $lines[ 0 ] == "SUCCESS", "Spot failed! Response: $response" );
            return $lines[ 1 ];
        }
        public static function CommentCreated( $userid, $itemid, $typeid ) {
            $userid = $comment->Userid;
            $itemid = $comment->Itemid;
            $typeid = $comment->Typeid;
            $request = "NEW COMMENT\n$userid\n$itemid\n$typeid\n";
            self::SendRequest( $request );
        }
        public static function VoteCreated( $vote ) {
            $userid = $vote->Userid;
            $pollid = $vote->Pollid;
            $optionid = $vote->Optionid;
            $request = "NEW VOTE\n$userid\n$pollid\n$optionid\n";
            self::SendRequest( $request );
        }
        public static function FavouriteCreated( $favourite ) {
            $userid = $favourite->Userid;
            $itemid = $favourite->Itemid;
            $typeid = $favourite->Typeid;
            $request = "NEW FAVOURITE\n$userid\n$itemid\n$typeid\n";
            self::SendRequest( $request );
        }
        public static function GetContent( $userid ) {
            $request = "GET CONTENT\n$userid\n";
            $content = self::SendRequest( $request );
            // TODO: process content somehow?

            return $content;
        }
        public static function GetSamecom( $auser, $buser ) { // for testing only.
            $request = "GET SAMECOM\n$auserid\n$buserid\n";
            $samecom = (int)( self::SendRequest( $request ) );
            return $samecom;
        }
    }

?>
