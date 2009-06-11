<?php

    /*
    SPOT - Social Prediction And Optimization Tool
    Class for connecting to the Spot deamon.

    Coming soon: unit tests!
    */

    define( 'SPOT_PORT', 21490 );


    class Spot {
        private static $mRequestHeader = "SPOT\n";

        public function __construct() {
            // do nothing! static methods
        }
        private static function SendRequest( $requestBody ) {
            // TODO: Response Text
            // TODO: Error checking

            $request = self::$mRequestHeader . $requestBody;
            
            $sock = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
            socket_bind( $sock, '127.0.0.1' );
            socket_connect( $sock, 'europa.kamibu.com', SPOT_PORT );
            socket_write( $sock, $request );
            socket_close( $sock );
        }
        public static function CommentCreated( $userid, $itemid, $typeid ) {
            $request = "NEW COMMENT\n$userid\n$itemid\n$typeid\n";
            self::SendRequest( $request );
        }
        public static function VoteCreated( $userid, $pollid, $optionid ) {
            $request = "NEW VOTE\n$userid\n$pollid\n$optionid\n";
            self::SendRequest( $request );
        }
        public static function FavouriteCreated( $userid, $itemid, $typeid ) {
            $request = "NEW FAVOURITE\n$userid\n$itemid\n$typeid\n";
            self::SendRequest( $request );
        }
        public static function GetContent( $userid ) {
            $request = "GET CONTENT\n$userid\n";
            $content = self::SendRequest( $request );
            // TODO: process content somehow?

            return $content;
        }
    }

?>
