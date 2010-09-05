<?php

    /*
    SPOT - Social Prediction And Optimization Tool
    Class for connecting to the Spot deamon.

    Coming soon: unit tests!
    */
    
    class Spot {
        private static $mRequestHeader = "SPOT\n";

        public function __construct() {
            // do nothing! static methods
        }
        private static function SendRequest( $requestBody ) {
            global $settings;

            // TODO: Response Text
            // TODO: Error checking

            if ( $settings[ 'spotdaemon' ][ 'enabled' ] == false ) {
                return false;
            }

            Water::ProfileStart( 'Spot request time' );

            $request = self::$mRequestHeader . $requestBody;
            
            $sock = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
            // w_assert( $sock !== false, "Socket creation failed. Reason: " . socket_strerror( socket_last_error( $sock ) ) );
            if ( $sock === false ) {
                return false;
            }

            //$result = socket_connect( $sock, $xc_settings[ 'spotdaemon' ][ 'address' ], $xc_settings[ 'spotdaemon' ][ 'port' ] );
            $result = @socket_connect( $sock, $settings[ 'spotdaemon' ][ 'address' ], $settings[ 'spotdaemon' ][ 'port' ] );
            // w_assert( $result !== false, "Spot connection failed. Run spot daemon." );
            if ( $result === false ) {
                socket_close( $sock );
                return false;
            }

            socket_write( $sock, $request );

            $response = socket_read( $sock, 1024 );
            socket_close( $sock );

            Water::ProfileEnd();

            $lines = explode( "\n", $response );
            //w_assert( $lines[ 0 ] == "SUCCESS", "Spot failed! Response: $response" );
            array_shift( $lines ); // success message
            array_pop( $lines ); // useless last line exploded
            return $lines;
        }
        public static function CommentCreated( $comment ) {
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
        public static function GetContent( $user, $numImages = 30, $numJournals = 10, $numPolls = 10 ) {
            global $libs;
            $libs->Load( 'image/image' );
            $libs->Load( 'journal/journal' );
            $libs->Load( 'poll/poll' );

            $userid = $user->Id;
            $request = "GET CONTENT\n$userid\n$numImages\n$numJournals\n$numPolls\n";
            $content = self::SendRequest( $request );
            // TODO: process content somehow?

            $lines = self::SendRequest( $request );
            if ( $lines === false ) {
                return $lines;
            }

            $imageids = array();
            $journalids = array();
            $pollids = array();
            $idToOrder = array();
            foreach ( $lines as $order => $line ) {
                list( $type, $id ) = explode( ":", $line );
                $idToOrder[ $type ][ $id ] = $order;
                switch ( $type ) {
                    case 'image':
                        $imageids[] = $id;
                        break;
                    case 'journal':
                        $journalids[] = $id;
                        break;
                    case 'poll':
                        $pollids[] = $id;
                        break;
                }
            }

            $finder = New ImageFinder();
            $images = $finder->FindByIds( $imageids );

            $finder = New JournalFinder();
            $journals = $finder->FindByIds( $journalids );

            $finder = New PollFinder();
            $polls = $finder->FindByIds( $pollids );

            $content = array();
            foreach ( $images as $image ) {
                $content[ $idToOrder[ 'image' ][ $image->Id ] ] = $image;
            }
            foreach ( $journals as $journal ) {
                $content[ $idToOrder[ 'journal' ][ $journal->Id ] ] = $journal;
            }
            foreach ( $polls as $poll ) {
                $content[ $idToOrder[ 'poll' ][ $poll->Id ] ] = $poll;
            }

            return $content;
        }
        public static function GetJournals( $userid, $num = 10 ) {
            $userid = ( int )$userid;
            $request = "GET JOURNALS\n$userid\n$num\n";
            $lines = self::SendRequest( $request );
            if ( $lines === false ) {
                return $lines;
            }

            $ids = array();
            foreach ( $lines as $id ) {
                $ids[] = $id;
            }

            return $ids; // journal ids
        }
        public static function GetImages( $userid, $num = 30 ) {
            //global $water;

            //clude( 'models/photo.php' );

            //$water->Profile( 'Spot get images' );

            $request = "GET IMAGES\n$userid\n$num\n";
            $lines = self::SendRequest( $request );
            if ( $lines === false ) {
                return $lines;
            }

            $ids = array();
            foreach ( $lines as $id ) {
                $ids[] = $id;
            }
            //$content = Photo::ListByIds( $ids );

            //$water->ProfileEnd();

            return $ids;
        }
        public static function GetPolls( $userid, $num = 10 ) {
            $userid = ( int )$userid;
            $request = "GET POLLS\n$userid\n$num\n";
            $lines = self::SendRequest( $request );
            if ( $lines === false ) {
                return $lines;
            }

            $ids = array();
            foreach ( $lines as $id ) {
                $ids[] = $id;
            }

            return $ids; // journal ids
        }
        public static function GetSamecom( $auser, $buser ) { // for testing only.
            $auserid = $auser->Id;
            $buserid = $buser->Id;
            $request = "GET SAMECOM\n$auserid\n$buserid\n";
            $response = self::SendRequest( $request );
            $samecom = (int)( $response[ 0 ] );
            return $samecom;
        }
        public static function GetUniquecoms( $user ) { // for testing only.
            $userid = $user->Id;
            $request = "GET UNIQUECOMS\n$userid\n";
            $response = self::SendRequest( $request );
            $uniquecoms = (int)( $response[ 0 ] );
            return $uniquecoms;
        }
    }

?>
