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
	                <th></th>
	                <th></th>
                </tr>
            <?php
	        foreach( $notapproved as $school ) {
	            ?><tr><td><?php            
	            echo $school->Name;        
	            ?></td><td><?php
	            echo $school->Place->Name;
	            ?></td><td><?php
	            ?><form method="post" action="do/adminpanel/schools/approve"><?php
	            ?><input type="submit" value="Αποδοχή" /><?php
                ?><input type="hidden" name="userid" value="<?php
                echo $school->Id;
                ?>" /><?php
                ?></form><?php
                ?></td><td><?php
	            ?><form method="post" action=""><?php
	            ?><input type="submit" value="Κατάργηση" /><?php
                ?><input type="hidden" name="id" value="<?php
                echo $school->Id;
                ?>" /><?php
                ?></form><?php
	            ?></td></tr><?php
	        }
	        ?></table><?php

        }
    }
?>
