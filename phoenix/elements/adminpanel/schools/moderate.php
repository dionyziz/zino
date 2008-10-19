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
	        
	        ?><ul><?php
	        foreach( $notapproved as $school ) {
	            ?><li>Όνομα : <?php    
	            echo $school->Name;        
	            ?> Περιοχή : <?php
	            echo $school->Place->Name;
	            ?></li><?php
	        }
	        ?></ul><?php
	           
        }
    }
?>
