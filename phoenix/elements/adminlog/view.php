<?php
    class ElementAdminlogView extends Element {
        public function Render() {
            global $user;
            global $libs;
            global $page;
            
            $libs->Load( 'adminpanel/adminaction' );
            $page->setTitle( 'Logged admin actions' );
            
            if ( !$user->hasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
                ?>Permission Denied<?php
                return;
            }
            
            ?><h2>Logged admin actions</h2><?php 
            
            $adminFinder = new AdminActionFinder();
            $admins = $adminFinder->FindAll( 0, 20 );            
           
            foreach ( $admins as $admin ) {
            echo '<p>' . $admin->id . ' ' . $admin->userid . ' ' . $admin->date . '</p>';
            }
                        
            return;
        }
    }
?>
