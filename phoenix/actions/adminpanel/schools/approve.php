<?php
    function ActionAdminpanelSchoolsApprove( tText $schoolid ) {
        global $libs;
        
        $schoolid = $schoolid->Get();
        
        $libs->Load( 'school/school' );
        
        $school = new School( $schoolid );        
        $school->Approved = 1;
           
        return Redirect( '?p=moderateschools' );
    }
?>
