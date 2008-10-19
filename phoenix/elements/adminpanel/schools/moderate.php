<?php    
    class ElementAdminpanelSchoolsModerate extends Element {
        public function Render() {
	        global $page;
	        global $user;
	        global $libs;
	        
	        if( ! $user->HasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
	            ?> Permission Denied <?php
	            return;
	        }
	        
	        $page->setTitle( 'Διαχείρηση Σχολείων' );
	        
	        ?><h2>Διαχείρηση Σχολείων</h2><?php
	        
	        $libs->Load( 'school/school' );
	        
	        $schoolFinder = new SchoolFinder();
	        $notapproved = $schoolFinder->FindNotApproved();
	        
	        
	        ?><table class="stats">
	            <tr>
	                <th>Όνομα</th>
	                <th>Περιοχή</th>
	                <th>Αποδοχή</th>
                </tr>
            <?php
	        foreach( $notapproved as $school ) {
	            ?><tr><td><?php            
	            echo $school->Name;        
	            ?></td><td><?php
	            echo $school->Place->Name;
	            ?></td></tr><?php
	        }
	        ?></table><?php

        }
    }
?>
