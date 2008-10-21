<?php
    function ActionAdminpanelSchoolsApprove( tText $schoolid ) {
        global $libs;
        
        $schoolid = $schoolid->Get();
        
        $libs->Load( 'school/school' );
        
        $school = new School( $schoolid );    
        if ( $school->Exists ) {
            $school->Approved = 1;
            $school->Save();
        }
           
        return Redirect( '?p=moderateschools' );
    }
?>
