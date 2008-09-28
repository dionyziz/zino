<?php
    function ActionFAQQuestionDelete( tInteger $id ) {
    	global $user;
    	global $libs;
    	
    	$libs->Load( 'faq' );
    	
    	if ( !FAQ_CanModify( $user ) || $id->Get() == 0 ) {
    		return Redirect( "?p=404" );
    	}
    	
    	$question = New Faq_Question( $id->Get() );
    	$action = $question->Kill();
    	
    	if ( $action ) {
    		return Redirect( '?p=faq' );
    	}
    	
        return Redirect( '?p=faqq&id=' . $id->Get() . '&error=yes' );
    }
?>
