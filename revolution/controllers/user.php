<?php
    class ControllerUser {
        public static function View( $id = false, $subdomain = false, $verbose = 3, $commentpage = 1 ) {
            $id = ( int )$id;
            $commentpage = ( int )$commentpage;
            $commentpage >= 1 or die;
            clude( 'models/db.php' );
            clude( 'models/user.php' );
            if ( $verbose >= 3 ) {
                if ( $id ) {
                    $user = User::ItemDetails( $id );
                }
                else if ( $subdomain ) {
                    $user = User::ItemDetailsBySubdomain( $subdomain );
                }
                else die;
                $countcomments = $user[ 'numcomments' ];
            }
            else {
                if ( $id ) {
                    $user = User::Item( $id );
                }
                else if ( $subdomain ) {
                    $user = User::ItemBySubdomain( $subdomain );
                }
                $countcomments = 0; // TODO: remove this line
            }
            $user !== false or die;
            if ( $user[ 'userdeleted' ] === 1 ) { 
                include 'views/itemdeleted.php';
                return;
            }
            if ( $verbose >= 3 ) {
                clude( 'models/comment.php' );
                clude( 'models/activity.php' );
                $commentdata = Comment::FindByPage( TYPE_USERPROFILE, $user[ 'id' ], $commentpage );
                $numpages = $commentdata[ 0 ];
                $comments = $commentdata[ 1 ];
                $counts = UserCount::Item( $id );
                // $activity = Activity::ListByUser( $id );
            }
            include 'views/user/view.php';
        }
        public static function Listing() {
            clude( 'models/db.php' );
            clude( 'models/user.php' );
            $users = User::ListOnline();
            include 'views/user/listing.php';
        }
        public static function Create() {
        }
        public static function Update() {
        }
        public static function Delete() {
        }
    }
?>
