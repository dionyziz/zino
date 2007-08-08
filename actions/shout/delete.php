<?php
    function ActionShoutDelete( tString $action, tInteger $id, tString $reason ) {
    	global $user;
    	global $libs;
    	
    	$libs->Load( 'shoutbox' );
    	
    	if ( !$user->CanModifyStories() ) {
    		return Redirect();
    	}
    	
    	$action = $action->Get();
    	$id = $id->Get();

    	if ($action == "undelete" ) {
    		if ( !$user->CanModifyCategories() ) {
                return Redirect();
    		}
    		DeleteShout( $id, '', 'undelete' );
			$shout = new Shout( $id );
			$shout->UndoDelete();
            return Redirect();
    	}
    	else {	
    		$reason = $reason->Get();
    		
    		if ( $user->CanModifyStories() ) {
    			if ( $id ) {
				
					$shout = new Shout( $id );
    				$shout->Delete();
					
                    return Redirect();
    			}
    			else {
    				?>Error: no shout id selected<?php
    			}
    		}
    		else {
                return Redirect();
    		}
    	}
    }
?>
