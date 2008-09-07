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
            
            $ban = new Ban();
            //$res = $ban->BanUser( 'kapios123' );
            
            ?><p><?php
            //echo $res;
            ?></p><?php
            
            return;
        }
    }
?>
