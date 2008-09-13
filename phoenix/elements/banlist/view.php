<?php
    class ElementBanlistView extends Element {
        public function Render() {
            global $user;
            global $libs;
            global $page;
            
            if ( !$user->hasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
                ?> Permission Denied <?php
                return;
            }
            
            $libs->Load( 'adminpanel/ban' );
            $libs->Load( 'adminpanel/bannedusers' );
            
            $page->setTitle( 'List of banned members' );
            
            ?><h2>Banned users</h2><?php         
            
            $bannedUserFinder = new BannedUserFinder();
            $bannedUsers = $bannedUserFinder->FindAll( 0, 20 );
            
            foreach ( $bannedUsers as $bannedUser ) {
                ?><p>User <?php
                echo $bannedUser->userid;
                ?> was banned at <?php
                echo $bannedUser->started;
                ?> and delalbum is <?php
                echo $bannedUser->delalbums;
                ?>.</p><?php
            }
                        
            $ban = new Ban();
            $res = $ban->BanUser( 'test5' );
            
            if( $res ) {
                ?><p>Success</p><?php
            }
            else {
                ?><p>Failure</p><?php
            } 
            
            return;
        }
    }
?>
