<?php
    function ActionAdminpanelSchoolsApprove( tInteger $userid ) {
        global $libs;
        
        $userid = $userid->Get();
        
        $libs->Load( 'school/school' );
        
        $school = new School( $userid );
        if ( $school->Exists ) {
            $school->Approved = 1;
        }
           
        return Redirect( '?p=moderateschools' );
    }
?>
