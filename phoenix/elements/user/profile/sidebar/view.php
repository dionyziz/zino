<?php
    class ElementUserProfileSidebarView extends Element {
        public function Render( $theuser ) {
            //global $rabbit_settings;
            global $libs;
            global $user;
            global $page;
            
            $libs->Load( 'relation/relation' );
            
            ?><div class="sidebar">
                <div class="basicinfo"><?php
                    Element( 'user/profile/sidebar/basicinfo' , $theuser , $theuser->Id , $theuser->Profile->Updated ); 
                    if ( $user->Id != $theuser->Id && $user->Exists() ) {
                        $finder = New FriendRelationFinder();
                        $res = $finder->FindFriendship( $user , $theuser );
                        if ( !$res ) {
                            $page->AttachInlineScript( 'Profile.ShowFriendLinks( true , " ' .$theuser->Id . ' " );' );
                        }
                        else {
                            $page->AttachInlineScript( 'Profile.ShowFriendLinks( false , " ' . $res->Id . ' " );' );
                        }                    
                    }
					if ( $theuser->LastActivity->Updated != '0000-00-00 00:00:00' ) {
						?><dl class="online"><dt><strong>Online</strong></dt>
						<dd><?php
						if ( $theuser->LastActivity->IsOnline() ) {
							?>αυτή τη στιγμή!<?php
						}
						else {
							Element( 'date/diff' , $theuser->LastActivity->Updated );
						}
						?></dd></dl><?php
					}
                ?></div><?php
                Element( 'user/profile/sidebar/details' , $theuser , $theuser->Id , $theuser->Profile->Updated );
            ?></div><?php
        }
    }
?>
