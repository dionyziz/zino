<?php
    function ActionFAQCategoryDelete( tInteger $id ) {
    	global $user;
    	global $libs;
    	
    	$libs->Load( 'faq' );
    	
    	if ( !FAQ_CanModify( $user ) || $id->Get() == 0 ) {
    		return Redirect( "?p=404" );
    	}
    	
    	$category = New Faq_Category( $id->Get() );
    	$action = $category->Kill();
    	
    	if ( $action ) {
    		return Redirect( '?p=faq' );
    	}
    	
        return Redirect( '?p=faqc&id=' . $id->Get() . '&error=yes' );
    }
?>
