<?php
    function ActionRelationsNew( tString $type ) {
    	global $user;
    	global $libs;
    	
    	if( !$user->CanModifyCategories() ) {
    		echo "Δεν έχεις τα κατάλληλα δικαιώματα!";
    		return;
    	}
    	
    	$libs->Load( 'relations' );
    	
    	$type = $type->Get();
    	if( $type == '' || strlen( $type ) > 20 ) {
    		echo "Δεν παρείχες έγκυρη σχέση";
    		return;
    	}
    	$relation = New Relation();
    	$relation->Type = $type;
    	$relation->Save();
    	
    	return Redirect( '?p=frel' );
    }
?>
