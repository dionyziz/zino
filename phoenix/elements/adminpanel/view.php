<?php    
    class ElementAdminpanelView extends Element {
        public function Render( tText $username, tText $pass, tText $provider ) {
	        global $page;
	        global $user;
	        global $libs;
	        
	        if( ! $user->HasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
	            ?> Permission Denied <?php
	            return;
	        }
	        
	        $page->setTitle( 'Κεντρική σελίδα διαχειριστών' );
	        
	        ?><h2>Κεντρική σελίδα διαχειριστών</h2><?php
	        
	        ?><ul><?php
		        ?><li><a href="?p=statistics" >Στατιστικά στοιχεία του Zino</a></li><?php
		        ?><li><a href="?p=banlist" >Αποκλεισμένοι χρήστες</a></li><?php
		        ?><li><a href="?p=adminlog" >Ενέργειες διαχειριστών</a></li><?php
	        ?></ul><?php    
    
            $libs->Load( "admanager" );
            
            $adfinder = new AdFinder();
            $ads = $adfinder->FindAllActive();
            echo '<p>Title ' . count( $ads ) . '</p>';
            foreach ( $ads as $ad ) {
                ?><p><?php
                $ad->Userid;
                ?></p><?php
            }
            
        }
    }
?>
