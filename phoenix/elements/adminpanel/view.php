<?php    
    class ElementAdminpanelView extends Element {
        public function Render( tText $username, tText $pass, tText $provider ) {
	        global $page;
	        global $user;
	        global $libs;
		    global $xc_settings;
	        
	        if( ! $user->HasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
	            ?> Permission Denied <?php
	            return;
	        }
	        
	        $page->setTitle( 'Κεντρική σελίδα διαχειριστών' );
	        
	        ?><h2>Κεντρική σελίδα διαχειριστών</h2><?php
	        
	        ?><ul><?php		        
		        ?><li><a href="?p=banlist" >Αποκλεισμένοι χρήστες</a></li><?php
		        ?><li><a href="?p=adminlog" >Ενέργειες διαχειριστών</a></li><?php
		        ?><li><a href="?p=adviewer" >Ενεργές διαφημίσεις</a></li><?php
                        ?><li><a href="?p=happeningadmin" >Εκδηλώσεις</a></li><?php
		        ?><li><a href="?p=statistics" >Στατιστικά( νέα σχόλια, δημοσκοπήσεις, χρήστες... )</a></li><?php
                        ?><li><a href="?p=memorystats" >Στατιστικά σχετικά με την χρήση μνήμης απο τα διάφορα scripts</a></li><?php
	        ?></ul><?php
	        
	        $libs->Load( 'research/spot' );
            ?><p>Content</p><?php
			$spot = New Spot();
			$res = $spot->GetJournalsExtended( $user );
			var_dump( $res );
			return;
        }
    }
?>
