<?php
	function UnitPlaceDelete( tInteger $placeid ) {
		global $user;
		global $libs;
		
        if ( !$user->CanModifyCategories() ) {
            return;
        }
        
		$libs->Load( 'place' );
        
        $placeid = $placeid->Get();
        $place = New Place( $placeid );
        $place->Delete();
        
        ?>g( 'place_<?php
        echo $placeid;
        ?>' ).style.display = 'none';<?php
	}

?>
