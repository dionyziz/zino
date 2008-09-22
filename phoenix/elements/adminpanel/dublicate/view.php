<?php
    class ElementAdminpanelDublicateView extends Element {
        public function Render() {
            global $libs;
            global $user;
            global $page;
            global $db;
                
            if ( ! $user->HasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
                ?> Permission Denied <?php
                return;
            }
            
            $page->setTitle( 'Dublicate accounts' );
            
            $libs->Load( 'adminpanel/dublicate' );
            
            ?><h2>Dublicate Accounts</h2><?php
            
            $dub = new DublicateAccount();
            $res = $dub->getDublicateAccountsByUserName( 'pagio91' );
            
            foreach ( $res as $dub ) {
                ?><p><?php
                echo $dub;                
                ?></p><?php
            }
       }
    }
?>
