<?php
    function ActionAdminpanelSchoolsApprove( tText $userid ) {
        global $libs;
        
        $userid = $userid->Get();
        
        $libs->Load( 'school/school' );
        
        $school = new School( $userid );        
        $school->Approved = 1;
           
        return Redirect( '?p=moderateschools' );
    }
?>
