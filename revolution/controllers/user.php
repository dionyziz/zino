<?php
    class ControllerUser {
        public static function View( $id = false, $subdomain = false, $name = false, $verbose = 3, $commentpage = 1 ) {
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
                else if ( $name ) {
                    $user = User::ItemDetailsByName( $name );
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
                else if ( $name ) {
                    $user = User::ItemByName( $name );
                }
                else die;
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
                clude( 'models/friend.php' );
                clude( 'models/music/song.php' );

                $commentdata = Comment::ListByPage( TYPE_USERPROFILE, $user[ 'id' ], $commentpage );
                $numpages = $commentdata[ 0 ];
                $comments = $commentdata[ 1 ];
                $counts = UserCount::Item( $user[ 'id' ] );
                $activity = Activity::ListByUser( $user[ 'id' ] );
                $song = Song::Item( $user[ 'id' ] );
                if ( $song === false ) {
                    unset( $song );
                }
                $friendofuser = false;
                if ( isset( $_SESSION[ 'user' ] ) ) {
                    $friendofuser = ( bool ) ( Friend::Strength( $_SESSION[ 'user' ][ 'id' ], $user[ 'id' ] ) & FRIENDS_A_HAS_B );
                }
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
        public static function Update(
            $gender = false, $email = false, $placeid = 0, $dob = '', $slogan = false, $schoolid, $sexualorientation, $relationship, $religion, $politics, $aboutme, $moodid, $eyecolor, $haircolor, $height, $weight, $smoker, $drinker, $favquote, $mobile, $skype, $msn, $gtalk, $yim, $homepage, $firstname, $lastname, $address, $addressnum, $postcode, $area, $numcomments, $education, $educationyear, $songid, $songwidgetid ) {
            switch ( $gender ) {
                case 'm':
                case 'f':
                case '-':
                    break;
                default:
                    $gender = '';
            }
        }
        public static function Delete() {
        }
    }
?>
