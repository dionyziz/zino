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
            $friends = $finder->FindByUser( $theuser , ( $pageno - 1 )*24 , 24 );
            Element( 'user/sections', 'relations' , $theuser );
            ?><div id="relations"><?php
                if ( !empty( $friends ) ) {
                    Element( 'user/list' , $friends );
                }
                else {
                    ?>Δεν έχουν προστεθεί φίλοι<?php
                }
                ?><div class="pagifyrelations"><?php
                
                $link = str_replace( '*', urlencode( $theuser->Subdomain ), $xc_settings[ 'usersubdomains' ] ) . 'friends?pageno=';
                $total_friends = $theuser->Count->Relations;
                $total_pages = ceil( $total_friends / 24 );
                Element( 'pagify', $pageno, $link, $total_pages, "( " . $total_friends . " Φίλοι )" );

                ?></div>
                <div class="eof"></div>
            </div><?php
        
        }
    }
?>
