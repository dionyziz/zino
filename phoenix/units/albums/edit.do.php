<?php
	function UnitAlbumsEdit( tInteger $albumid , tString $newtext , tInteger $typeid ) {
		global $water;
		global $user;
		global $libs;
		/*
			typeid:
				0 for albumname
				1 for albumdescription
			islist:
				true -> albumlist
				false -> albumsmall
		*/
        
        $albumid = $albumid->Get();
        $newtext = $newtext->Get();
        $typeid = $typeid->Get();
        
		$libs->Load( 'albums' );
		$album = New Album( $albumid );
		if ( $album->UserId() == $user->Id() ) {
			$newtexten = w_json_encode( $newtext );
			$nalbumid = w_json_encode( $album->Id() );
			if ( $typeid == 0 ) {
				$album->UpdateName( $newtext );
			}
			elseif ( $typeid == 1 ) {
				$album->UpdateDescription( $newtext );
			}	
		}
		
	}
?>