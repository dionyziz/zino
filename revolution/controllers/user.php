<?php
    class ControllerUser {
        public static function View( $id = false, $name = false, $details = 'yes', $commentpage = 1 ) {
            if ( $name ) {
                $name = ( string ) $name;
            }
            elseif ( $id ) {
                $id = ( int )$id;
            }
            else {
                die;
            }
            $commentpage = ( int )$commentpage;
            $commentpage >= 1 or die;
            include 'models/db.php';
            include 'models/user.php';
            if ( $details == 'yes' && $id ) { //TODO: Only works with a given id, the model must be updated
                $user = User::ItemDetails( $id );
                $countcomments = $user[ 'numcomments' ];
            }
            else {
                if ( $id ) {
                    $user = User::Item( $id );
                }
                elseif ( $name ) {
                    $user = User::ItemByName( $name );
                }
            }
            $user !== false or die;
            if ( $user[ 'userdeleted' ] === 1 ) { 
                include 'views/itemdeleted.php';
                return;
            }
            if ( $details == 'yes' ) {
                include 'models/comment.php';
                $commentdata = Comment::FindByPage( TYPE_USERPROFILE, $user[ 'id' ], $commentpage );
                $numpages = $commentdata[ 0 ];
                $comments = $commentdata[ 1 ];
            }
            include 'views/user/view.php';
        }
        public static function Listing() {
            include 'models/db.php';
            include 'models/user.php';
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
