<?php
    function ActionRelationsNew( tString $type ) {
    	global $user;
    	global $libs;
    	
    	if( !$user->CanModifyCategories() ) {
    		return;
    	}
    	
    	$libs->Load( 'relations' );
    	
    	$type = $type->Get();
    	if( $type == '' || strlen( $type ) > 30 ) {
    		return;
    	}
    	$relation = New Relation();
    	$relation->Type = $type;
    	$relation->Save();
    	
    	return Redirect( '?p=frel' );
    }
?>
