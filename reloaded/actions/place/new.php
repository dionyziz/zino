<?php
    function ActionPlaceNew( tString $name, tInteger $eid ) {
    	global $libs;
    	global $user;
    	
    	$libs->Load( 'place' );
    	
    	if ( !( $user->CanModifyCategories() ) ) {
            return Redirect();
    	}
        $name 		= $name->Get();
        $eid		= $eid->Get();
        if ( $name ) {
            if ( $eid > 0 ) {
                $place = New Place( $eid );
            }
            else {
                $place = New Place();
            }
            $place->Name = $name;
            $place->Save();
        }
        return Redirect( '?p=places' );
    }
?>
