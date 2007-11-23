<?php
	function UnitUsersOptionsProfileColor( tInteger $r, tInteger $g, tInteger $b ) {
        global $user;
        
        $r = $r->Get();
        $g = $g->Get();
        $b = $b->Get();
        
        if (    $r > 255 || $g > 255 || $b > 255 
             || $r <   0 || $g <   0 || $b <   0 ) {
            return;
        }
        $user->SetProfileColor( Color_Encode( $r, $g, $b ) );
    }
?>
