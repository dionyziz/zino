<?php
    function ActionFAQCategoryNew( tString $name, tString $description, tInteger $eid ) {
    	global $user;
    	global $libs;
    	
        $name = $name->Get();
        $description = $description->Get();
        $eid = $eid->Get();
        
    	$libs->Load( 'faq' );
    	
    	if ( !FAQ_CanModify( $user ) ) {
    		return Redirect( "?p=404" );
    	}
    	
    	if ( empty( $name ) ) {
            return Redirect( '?p=addfaqc&noname=yes' );
    	}
    	if ( empty( $description ) ) {
    		return Redirect( '?p=addfaqc&nodescription=yes' );
    	}
    	
    	if ( !empty( $_FILES[ 'icon' ][ 'name' ] ) ) {
    		$iconid = FAQ_UploadIcon( $_FILES[ 'icon' ] );
    	}
    	else {
    		$iconid = 0;
    	}
    	
    	if ( $eid != 0 ) {
    		$category = New FAQ_Category( $eid );
    		$category->Update( $name, $description, $iconid );
    		$action = $category->Id();
    	}
    	else {
    		$action = FAQ_MakeCategory( $name, $description, $iconid );
    	}
    	
    	if ( $action > 0 ) {
    		return Redirect( '?p=faqc&id=' . $action );
    	}
    	
        return Redirect( '?p=faqc&error=yes' );
    }
?>
