<?php
	
	function UnitUserAvatar( tInteger $imageid ) {
		global $user;
		
		$image = New Image( $imageid->Get() );
		
		if ( !$image->IsDeleted() ) {
			if ( $image->User->Id == $user->Id ) {
				$egoalbum = New Album( $user->Egoalbumid );
				if ( $egoalbum->Mainimage != $image->Id ) {
					$egoalbum->Mainimage = $image->Id;
					$egoalbum->Save();
					?>$( 'div.sidebar div.basicinfo h2 img' ).attr( {
						src : ExcaliburSettings.photosurl + '<?php
						echo $user->Id;
						?>/<?php
						echo $image->Id;
						?>?resolution=150x150x&sandbox=yes'
					} );<?php
				}
			}
		}
	}
?>
