<?php
    
    class ElementAdmin_panelView extends Element
    {
    
        public function Render () {
        global $page;
        global $user;
        
        if( ! $user->HasPermission( PERMISSION_ADMIN_PANEL_VIEW ) ) {
        ?> Permission Denied <?php
        return;
        }
        
        $page->setTitle( 'Administration Panel' );
        
        ?> <h2>Administrtion Panel</h2> <?php
        
        ?> <ul> <?php
        ?> <li><a href="?p=statistics" > Daily Statistics </a></li> <?php
        ?> <li><a href="?p=ban" > Ban list(not implemented yet) </a></li> <?php
        ?> <li><a href="?=admin_log" > Admin's log(not implemented yet) </a></li> <?php
        ?> </ul> <?php
        
        }
    }
?>
        
        
        
