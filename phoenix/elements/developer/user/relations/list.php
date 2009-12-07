<?php
    class ElementDeveloperUserRelationsList extends Element {
        public function Render( tText $username , tText $subdomain , tInteger $pageno ) {
            global $libs;
            global $user;
            global $page;
            global $xc_settings;
            
            Element( 'developer/user/subdomainmatch' );
            
            $libs->Load( 'relation/relation' );
            $libs->Load( 'user/profile' );
            
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

            if ( $theuser->Deleted ) {
                $libs->Load( 'rabbit/helpers/http' );
                return Redirect( 'http://static.zino.gr/phoenix/deleted' );
            }
            if ( Ban::isBannedUser( $theuser->Id ) ) {
                $libs->Load( 'rabbit/helpers/http' );
                return Redirect( 'http://static.zino.gr/phoenix/banned' );
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
            $friends = $finder->FindArrayByUser( $theuser , 0 , 1024 );
            $userids = array();
            foreach ( $friends as $friend ) {
                $userids[] = $friend[ 'user_id' ];
            }
            $myfriends = $finder->AreFriends( $user, $userids );
            
            Element( 'developer/user/sections', 'relations' , $theuser );
            ?><div id="friends"><?php
                if ( !empty( $friends ) ) {
                    ?><span class="totalfriends"><?php
                    if ( $user->Id == $theuser->Id ) {
                        ?>Έχεις<?php
                    }
                    else {
                        if ( $theuser->Gender == 'f' ) {
                            ?>Η<?php
                        }
                        else {
                            ?>O<?php
                        }
                        ?> <?php
                        echo $theuser->Name;
                        ?> έχει<?php
                    }
                    ?> <span id="friendscount"><?php
                    echo count( $friends );
                    ?></span> <?php
                    if ( count( $friends ) ) {
                        ?> φίλους<?php
                    }
                    else {
                        ?> φίλο<?php
                    }
                    ?></span>
                    <ul class="friendlist"><?php
                    foreach ( $friends as $friend ) {
                        Element( 'developer/user/relations/row', $friend, $myfriends[ $friend[ 'user_id' ] ] );
                    }
                    ?></ul><?php
                }
                else {
                    ?>Δεν έχουν προστεθεί φίλοι<?php
                }
                ?><div class="eof"></div>
            </div><?php
            $page->AttachInlineScript( 'Friends.Load();' );
            if ( $user->Id == $theuser->Id ) {
                $page->AttachInlineScript( 'Friends.OwnSubdomain = true;' );
            }
        }
    }
?>
