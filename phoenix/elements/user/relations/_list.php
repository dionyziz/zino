<?php
    
    class ElementUserRelationsList extends Element {
        public function Render( tText $username , tText $subdomain , tInteger $pageno ) {
            global $libs;
            global $user;
            global $page;
            global $xc_settings;
            
            $libs->Load( 'relation/relation' );
            
            $username = $username->Get();
            $subdomain = $subdomain->Get();
            
            $finder = New UserFinder();
            if ( $username != '' ) {
                if ( strtolower( $username ) == strtolower( $user->Name ) ) {
                    $theuser = $user;
                }
                else {
                    $theuser = $finder->FindByName( $username );
                }
            }
            else if ( $subdomain != '' ) {
                if ( strtolower( $subdomain ) == strtolower( $user->Subdomain ) ) {
                    $theuser = $user;
                }
                else {
                    $theuser = $finder->FindBySubdomain( $subdomain );
                }
            }
            if ( !isset( $theuser ) || $theuser === false ) {
                ?>Ο χρήστης δεν υπάρχει<?php
                return;
            }        
            
            $pageno = $pageno->Get();
            if ( $pageno <= 0 ) {
                $pageno = 1;
            }
            if ( strtoupper( substr( $theuser->Name, 0, 1 ) ) == substr( $theuser->Name, 0, 1 ) ) {
                $page->SetTitle( $theuser->Name . " Φίλοι" );
            }
            else {
                $page->SetTitle( $theuser->Name . " φίλοι" );
            }
            
            $finder = New FriendRelationFinder();
            $friends = $finder->FindByUser( $theuser , 0 , 1024 );
            $userids = array();
            foreach ( $friends as $friend ) {
                $userids[] = $friend->Friend->Id;
            }
            $myfriends = $finder->AreFriends( $user, $userids );
            var_dump( $myfriends );
            die();
            
            Element( 'user/sections', 'relations' , $theuser );
            ?><div id="friends"><?php
                if ( !empty( $friends ) ) {
                    ?><ul class="friendlist"><?php
                    foreach ( $friends as $friend ) {
                        Element( 'user/relations/row', $friend, $myfriends[ $friend->User->Id ] );
                    }
                    ?></ul><?php
                }
                else {
                    ?>Δεν έχουν προστεθεί φίλοι<?php
                }
                ?><div class="eof"></div>
            </div><?php
        
        }
    }
?>
