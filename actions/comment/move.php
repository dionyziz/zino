<?php
    function ActionCommentMove( tInteger $cid, tInteger $sid ) {
    	global $user;
    	global $libs;
    	global $water;
        
    	$libs->Load( 'comment' );
    	
    	if ( $user->IsSysOp() ) {
    		$cid = $cid->Get();
    		$sid = $sid->Get();
    		
			$comm = New Comment( $cid );
    		$mc = $comm->MoveComment( $sid );

    		if ( $mc != 1 ) {
    			die( "MoveComment() Error: $mc" );
    		}
    		return Redirect( '?p=story&id=' . $sid );
    	}
        return Redirect();
    }
?>
