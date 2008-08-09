<?php    
    function ActionSpaceEdit( tText $text ) {
        global $user;
        global $libs;

        if ( !$user->Exists() ) {
            die( "You must login first" );
            
        }
        $text = $text->Get();
    
        $libs->Load( 'wysiwyg' );
        $result = WYSIWYG_PostProcess( $text );

        $user->Space->Text = $result;
        $user->Space->Save();

        ob_start();
        Element( 'user/url', $user->Id , $user->Subdomain );

        return Redirect( ob_get_clean() . 'space' );
    }
?>
