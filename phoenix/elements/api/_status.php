<?php
    class ElementApiStatus extends Element {
        public function Render( tText $user ) {
            global $libs;
            global $page;
            
            $libs->Load( 'user/user' );
            $libs->Load( 'user/statusbox' );
            
            $userfinder = New UserFinder();
            $theuser = $userfinder->FindBySubdomain( $user->Get() );
            
            if ( $theuser !== false ) {
                $tweetfinder = New StatusBoxFinder();
                $tweet = $tweetfinder->FindLastByUserId( $theuser->Id );
                if ( $tweet !== false ) {
                    ob_start();
                    if ( $gender == 'f' ) {
                        ?>Η <?php
                    }
                    elseif ( $userid == 872 ) {
                        ?>Το <?php
                    }
                    else {
                        ?>Ο <?php
                    }
                    echo $theuser->Name . ' ' . $tweet->Message;
                    $apiarray = ob_get_clean();
                }
                else {
                    $apiarray = false;
                }
                if ( !$xml ) {
                    echo w_json_encode( $apiarray );
                }
                else {
                    echo 'XML Zino API not yet supported';
                }
            }
        }
    }
?>