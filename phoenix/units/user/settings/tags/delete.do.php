<?php
    
    function UnitUserSettingsTagsDelete( tInteger $tagid ) {
        global $libs;
        global $user;
        
        $libs->Load( 'tag' );
        
        $tag = New Tag( $tagid->Get() );
        if ( $tag->Userid == $user->Id ) { 
            $tag->Delete();
        }
    }
?>
