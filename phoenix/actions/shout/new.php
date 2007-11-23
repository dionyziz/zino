<?php
    function ActionShoutNew( tString $shout, tInteger $id ) {
    	global $user;
    	global $libs;
    	
    	$shouttext = $shout->Get();
    	
    	$libs->Load( 'shoutbox' );
    	$new = preg_replace('/\r+/', '', $shouttext);
    	if ( !$user->IsModerator() || strlen($new) > 300) {
            return Redirect();
    	}
    	
        $id = $id->Get();
        if ( $id > 0 ) {
            $shout = New Shout( $id );
            if ( !$user->CanModifyStories() ) {
                if ( $shout->UserId() != $user->Id() ) {
                    // no permissions
                    return Redirect();
                }
            }
            $shout->Update( $shouttext );
        }
        else {
            MakeShout( $shouttext );
        }
        return Redirect();
    }
?>
