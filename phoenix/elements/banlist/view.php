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
            $bannedUsers = $bannedUsersFinder->FindAll( 0, 100 );
            
            foreach ( $bannedUsers as $bannedUser ) {
                ?><p>User <?php
                echo $bannedUser->userId;
                ?> was banned at <?php
                echo $bannedUser->started;
                ?> and delalbum is <?php
                echo $bannedUser->userId;
                ?>.</p><?php
            }
            
            $ban = new Ban();
            $res = $ban->BanUser( '---' );
            
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
