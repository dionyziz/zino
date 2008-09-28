<?php
    function ActionArticleDelete( tInteger $id ) {
    	global $libs;
    	global $user;
    	
    	$libs->Load( 'article' );
    	
		$id = $id->Get();
    	if ( $id == 0 ) {
            return Redirect();
    	}
    	
    	$article = New Article( $id );
    	
    	if ( !$article->CanModify( $user ) ) {
    		return Redirect();
    	}
    	
    	$article->Kill();
    	
    	return Redirect();
    }
?>
