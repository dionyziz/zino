<?php
    function ActionCommentChangeAuthor( tInteger $cid, tInteger $uid ) {
    	global $user;
    	global $water;
    	global $libs;
    	
    	$libs->Load( 'comment' );
    	
    	if ( $user->IsSysOp() ) {
    		$cid = $cid->Get();
    		$uid = $uid->Get();
			
    		$comment = New Comment( $cid );
            $comment->UserId = $uid;
            $comment->Save();

            return Redirect( $comment->Url );
    	}
        return Redirect();
    }
?>
