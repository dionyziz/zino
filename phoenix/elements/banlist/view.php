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
            
            $isbanned = new Ban();
            if( $isbanned->isBannedIp( UserIp() ) )
            echo '<p>'.'is banned'.'</p>';
            else
            echo '<p>'.'is not banned'.'</p>';
            
            $bannedUserFinder = new BannedUserFinder();
            $bannedUsers = $bannedUserFinder->FindAll( 0, 20 );
            
            foreach ( $bannedUsers as $bannedUser ) {
                ?><form method="post" action="do/adminpanel/revoke"><?php
                    ?><p>User <?php
                    echo $bannedUser->Userid;
                    ?> was banned at <?php
                    echo $bannedUser->Started;
                    ?> until <?php
                    echo $bannedUser->Expire;                
                    ?>.  <?php
                    ?><input type="submit" value="revoke" /><?php
                    ?><input type="hidden" name="userid" value="<?php
                    echo $bannedUser->Userid; 
                    ?>" /><?php
                    ?></p><?php
                ?></form><?php
            }

            ?><form method="post" action="do/adminpanel/ban"><?php
            ?><p>user name : <input type="text" name="username" /></p><?php
            ?><p><input type="submit" value="Ban" /></p><?php
            ?></form><?php
            
            return;
        }
    }
?>
