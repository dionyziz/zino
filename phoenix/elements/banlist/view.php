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
            
            $page->setTitle( 'List of banned members' );
            
            ?><h2>Banned ips</h2><?php
            
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
