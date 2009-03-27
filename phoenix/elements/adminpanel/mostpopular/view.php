<?php    
    class ElementAdminpanelMostpopularView extends Element {
        public function Render() {
	        global $page;
	        global $user;
	        global $libs;
	        
	        if( ! $user->HasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
	            ?> Permission Denied <?php
	            return;
	        }
	        
	        $page->setTitle( 'Popular' );
	        
	        ?><h2>Pop(ular)</h2><?php
        }
    }
?>
