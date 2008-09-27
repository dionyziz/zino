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
            
            $page->setTitle( 'Λιστα Αποκλεισμένων χρηστων' );
            
            ?><h2>Αποκλεισμένοι χρηστες</h2><?php     
            
            $bannedUserFinder = new BannedUserFinder();
            $bannedUsers = $bannedUserFinder->FindAllActive();
            
            ?><table>
                <tr>
                    <th>Χρήστης</th>
                    <th>Πότε</th>
                    <th>Εώς</th>
                    <th>Ενέργεια</th>
                </tr>
            <?php
            
            foreach ( $bannedUsers as $bannedUser ) {
                ?><tr><?php
                ?><form method="post" action="do/adminpanel/revoke"><?php
                    ?><th><?php
                    echo $bannedUser->Name;
                    ?></th><th><?php
                    echo $bannedUser->Started;
                    ?></th><th><?php
                    echo $bannedUser->Expire;                
                    ?></th><th><?php
                    ?><input type="submit" value="revoke" /><?php
                    ?><input type="hidden" name="userid" value="<?php
                    echo $bannedUser->Userid; 
                    ?>" /><?php
                    ?></th><?php
                ?></form><?php
                ?></tr><?php
            }

            ?><form method="post" action="do/adminpanel/ban"><?php
            ?><p>user name : <input type="text" name="username" /></p><?php
            ?><p><input type="submit" value="Ban" /></p><?php
            ?></form><?php
            
            return;
        }
    }
?>
