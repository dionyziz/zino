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
            
            foreach ( $bannedUsers as $bannedUser ) {
                ?><form method="post" action="do/adminpanel/revoke"><?php
                    ?><p>Ο χρήστης <?php
                    echo $bannedUser->Name;
                    ?> αποκλείστηκε στις <?php
                    echo $bannedUser->Started;
                    ?> μέχρι τις <?php
                    echo $bannedUser->Expire;                
                    ?>.  <?php
                    ?><input type="submit" value="Επαναφορά" /><?php
                    ?><input type="hidden" name="userid" value="<?php
                    echo $bannedUser->Userid; 
                    ?>" /><?php
                    ?></p><?php
                ?></form><?php
            }
            
            //table
            ?><table>
                <tr>
                    <th>Χρήστης</th>
                    <th>Πότε</th>
                    <th>Αιτία</th>
                </tr>
            <?php
            
            foreach ( $bannedUsers as $bannedUser ) {
                    ?><tr><?php
                    ?><td><?php
                    echo $bannedUser->Name;
                    ?></td><td><?php                    
                    Element( 'date/diff', $bannedUser->Started );              
                    ?></td><td><?php
                    ?><form method="post" action="do/adminpanel/revoke"><?php
                    ?>Δεν αναφέρθηκε  <?php
                    ?><input type="submit" value="Επαναφορά" /><?php
                    ?><input type="hidden" name="userid" value="<?php
                    echo $bannedUser->Userid;
                    ?>" /><?php
                    ?></form><?php
                    ?></td></tr><?php
            }
            ?></table><?php   
            //

            ?><form method="post" action="do/adminpanel/ban"><?php
            ?><p>Όνομα χρήστη : <input type="text" name="username" /></p><?php
            ?><p><input type="submit" value="Ban" /></p><?php
            ?></form><?php
            
            ?><br/><br/><br/><?php
            ?><p>Υποσημείωση : ο αποκλεισμός διαρκεί 20 ημέρες</p><?php
            
            
            return;
        }
    }
?>
