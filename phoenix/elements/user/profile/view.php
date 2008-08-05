<?php
    
    class ElementUserProfileView extends Element {
        public function Render( tText $name , tText $subdomain, tInteger $commentid , tInteger $pageno ) {
            global $page;
            global $user;
            global $water;
            
            $commentid = $commentid->Get();
            $pageno = $pageno->Get();
            $name = $name->Get();
            $subdomain = $subdomain->Get();
            $finder = New UserFinder();

            Element( 'user/subdomainmatch' );

            if ( $name != '' ) {
                if ( strtolower( $name ) == strtolower( $user->Name ) ) {
                    $theuser = $user;
                }
                else {
                    $theuser = $finder->FindByName( $name );
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
            $page->SetTitle( $theuser->Name );
            ?><div id="profile"><?php
                Element( 'user/profile/sidebar/view' , $theuser );
                Element( 'user/profile/main/view' , $theuser, $commentid, $pageno );
                ?><div class="eof"></div>
                <br />
                <div style="text-align:center">
                    <a href="http://www.gameplanet.gr/" style="margin:auto"><img src="http://static.zino.gr/images/ads/gameplanet-leaderboard.jpg" alt="Gameplanet" /></a>
                </div><br />
            </div><?php
        }
    }
?>
