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
                $tweet = $finder->FindLastByUserId( $userid );
                if ( $tweet !== false ) {
                    ob_start();
                    if ( $gender == 'f' ) {
                        ?>Ч <?php
                    }
                    elseif ( $userid == 872 ) {
                        ?>дя <?php
                    }
                    else {
                        ?>Я <?php
                    }
                    echo $name;
                    echo $tweet->Message;
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