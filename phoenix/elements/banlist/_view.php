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
            
            ?><table class="stats">
                <tr>
                    <th>Χρήστης</th>
                    <th>Πότε</th>
                    <th>Μέχρι</th>
                    <th>Αιτία</th>
                    <th></th>
                </tr>
            <?php            
            foreach ( $bannedUsers as $bannedUser ) {
                    ?><tr><td><?php
                    echo $bannedUser->Name;
                    ?></td><td><?php                    
                    Element( 'date/diff', $bannedUser->Started );              
                    ?></td><td><?php                    
                    echo $bannedUser->Expire; 
                    ?></td><td><?php
                    echo htmlspecialchars( $bannedUser->Reason );
                    ?></td><td><?php
                    ?><form method="post" action="do/adminpanel/revoke"><?php
                    ?><input type="submit" value="Επαναφορά" /><?php
                    ?><input type="hidden" name="userid" value="<?php
                    echo $bannedUser->Userid;
                    ?>" /><?php
                    ?></form><?php
                    ?></td></tr><?php
            }
            ?></table><?php   

            ?><form method="post" action="do/adminpanel/ban"><?php
            ?><p>
                        Όνομα χρήστη : <input type="text" maxlength="30" name="username" /> &nbsp;&nbsp;&nbsp;  
                        Λόγος Αποκλεισμού : <input type="text" maxlength="30" name="reason" /> &nbsp;&nbsp;&nbsp;
                        Διάρκεια : <input type="text" maxlength="5" value="20" name="time_banned" /> μέρες          
              </p>
              <p>
                        Μόνιμη διαγραφή ακόμα : &nbsp;&nbsp;&nbsp;  
                        Εικόνες <input type="checkbox" value="yes" name="delete_images" />&nbsp;&nbsp;
                        Δημοσκοπήσεις <input type="checkbox" value="yes" name="delete_polls" />&nbsp;&nbsp;
                        Ημερολόγια <input type="checkbox" value="yes" name="delete_journals" />&nbsp;&nbsp;
              </p>              
              <p><input type="submit" value="Ban" /></p>
             </form>
            <?php
   
            return;
        }
    }
?>
