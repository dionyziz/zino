<?php
    function ActionCommentChangeAuthor( tInteger $cid, tInteger $uid ) {
    	global $user;
    	global $water;
    	global $libs;
    	
    	$libs->Load( 'comment' );
    	
    	if ( $user->IsSysOp() ) {
    		$cid = $cid->Get();
    		$uid = $uid->Get();
			
    		$comm = New Comment( $cid );
    		$rc = $comm->ReauthorComment( $uid );
            return Redirect( "?p=story&id=" . $commentstory . "#comment_$rc" );
    	}
        return Redirect();
    }
?>
