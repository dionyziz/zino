<?php
    class ElementNotifyList extends Element {
        public function Render() {
            global $user;
            global $libs;
            
            $libs->Load( 'notify/notify' );
            
            if ( !$user->Exists() ) {
                ?>Πρέπει να είσαι συνδεδεμένος για να διαβάσεις τις ειδοποιήσεις σου!<?php
                return;
            }
            $finder = New NotificationFinder();
            $notifs = $finder->FindByUser( $user, 0, 8 );
            //$notifs->TotalCount() > 0
            
            ?><div id="notifylist"><?php
                
            ?></div><?php
        }
    }
?>