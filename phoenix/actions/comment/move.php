<?php
    function ActionCommentMove( tInteger $cid, tInteger $sid, tInteger $tid ) {
    	global $user;
    	global $libs;
    	global $water;
        
    	$libs->Load( 'comment' );
    	
    	if ( $user->IsSysOp() ) {
    		$cid = $cid->Get();
    		$sid = $sid->Get();
    		
			$comment = New Comment( $cid );
    		$comment->TypeId = $tid;
            $comment->PageId = $sid;
            $comment->Save();

    		if ( $mc != 1 ) {
    			die( "MoveComment() Error: $mc" );
    		}
    		return Redirect( $comment->Page->URL );
    	}
        return Redirect();
    }
?>
