<?php
    function UnitUserProfileFetchcontacts( tText $subdomain, tCoalaPointer $f ) {
        global $libs;
        
        $libs->Load( 'user/user' );
        $libs->Load( 'user/profile' );
        
        $subdomain->Get();
        $finder = New UserFinder();
        $theuser = $finder->FindBySubdomain( $subdomain );
        if ( !isset( $theuser ) || $theuser === false ) {
            ?>alert( "Υπήρξε πρόβλημα κατά την παρουσίαση των στοιχείων επικοινωνίας" );<?php
        }
        $profile = $theuser->Profile;
        ob_start();
        Element( 'user/profile/sidebar/contacts' , $profile->Skype , $profile->Msn , $profile->Gtalk , $profile->Yim );
        $html = ob_get_clean();
        echo $f;
        ?>(<?php
        echo w_json_encode( $html );
        ?>);<?php
    }
?>