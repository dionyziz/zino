<?php
    function ActionShoutboxNew( tText $text ) {
        global $user;
        global $libs;
        
        $libs->Load( 'wysiwyg' );
        $libs->Load( 'shoutbox' );
        
        $text = $text->Get();
        if ( $user->Exists() && trim( $text ) != '' ) {
            $shout = New Shout();
            $shout->Text = WYSIWYG_PostProcess( htmlspecialchars( $text ) );
            $shout->Save();
        }
        
        return Redirect( $_SERVER[ 'HTTP_REFERER' ]  );
    }
?>
