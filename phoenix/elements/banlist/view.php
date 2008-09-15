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
                ?><form method="post" action="do/adminpanel/revoke"><?php
                    ?><p>User <?php
                    echo $bannedUser->userid;
                    ?> was banned at <?php
                    echo $bannedUser->started;
                    ?> until <?php
                    echo $bannedUser->expire;                
                    ?>.  <?php
                    ?><input type="submit" value="revoke" /><?php
                    ?><input type="hidden" name="userid" value="<?php echo $bannedUser->userid; ?>" /><?php
                    echo  strtotime($bannedUser->expire) . ' ' .strtotime(NowDate()) . ' ' . (strtotime($bannedUser->expire) - strtotime(NowDate()));
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
