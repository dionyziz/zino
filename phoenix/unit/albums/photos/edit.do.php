<?php
	function UnitAlbumsPhotosEdit( tInteger $photoid , tString $newtext , tInteger $typeid ) {
		global $water;
		global $user;
		global $libs;
		
        $photoid = $photoid->Get();
        $newtext = $newtext->Get();
        $typeid = $typeid->Get();
        
		$libs->Load( 'image/image' );
		$photo = New Image( $photoid );
		if ( $photo->UserId() == $user->Id() ) {
            switch ( $typeid ) {
                case 0:
                    $photo->UpdateName( $newtext );
                    break;
                case 1:
                    $photo->UpdateDescription( $newtext );
			}
		}
	}
?>