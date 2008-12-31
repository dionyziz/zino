<?php
    function ActionShoutboxNew( tText $shout ) {
        global $user;
        global $libs;
        
        $libs->Load( 'wysiwyg' );
        $libs->Load( 'shoutbox' );
        
        $text = $text->Get();
        if ( !$user->Exists() || trim( $text ) == '' ) {
            return Redirect();
        }
        
        $shout = New Shout();
        $shout->Text = WYSIWYG_PostProcess( htmlspecialchars( $text ) );
        $shout->Save();
        
        return Redirect();
    }
?>
