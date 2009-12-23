<?php
    class ElementStoreAdmin extends Element {
        public function Render() {
            
            global $user;
	        if( ! $user->HasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
	            ?> Permission Denied <?php
	            return;
	        }

            ?><a href="?p=store/manager">Purchase Manager</a><?php
        }
    }
?>
