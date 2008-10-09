<?php    
    class ElementAdminpanelView extends Element {
        public function Render() {
	        global $page;
	        global $user;
	        
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
	        
	        global $libs;
	        $libs->Load( 'user/user' );
	        //$libs->Load( 'bennu/bennu' );
	        $libs->Load( 'bennu/mybennu' );
	        
	        $userFinder = new UserFinder();
	        $input = $userFinder->FindLatest();
	        $target = $userFinder->FindByName( 'kostis90' );
	        
	        $res = Bennu_OnlineNow( $target, $input );

	        ?><h3>Results</h3><?php
	        foreach ( $res as $sample ) {
	            echo '<p>'.$sample->Name.'</p>';
            
            }
        }
    }
?>
