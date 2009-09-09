<?php
    function ActionAdminpanelSchoolsReject( tInteger $schoolid ) {
        global $libs;
        
        $schoolid = $schoolid->Get();
        
        $libs->Load( 'school/school' );
        
        $school = New School( $schoolid );        
        if ( $school->Exists() ) {
            $school->Delete();  
        }
        
        return Redirect( '?p=moderateschools' );
    }
?>
