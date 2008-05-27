<?php
	
	function UnitUserSettingsAvatar( tInteger $imageid ) {
		global $user;
		
		$image = New Image( $imageid->Get() );
		
		if ( !$image->IsDeleted() ) {
			if ( $image->User->Id == $user->Id ) {
				$egoalbum = New Album( $user->Egoalbumid );
				if ( $egoalbum->Mainimage != $image->Id ) {
					$egoalbum->Mainimage = $image->Id;
					$egoalbum->Save();
					?>$( 'div.settings div.tabs form#personalinfo div.option div.setting img.avie' ).attr( {
						src : ExcaliburSettings.photosurl + '<?php
						echo $user->Id;
						?>/<?php
						echo $image->Id;
						?>?resolution=150x150&sandbox=yes'
					} );
					setTimeout( "Frontpage.HideUpload" , 1000 );<?php
				}
			}
		}
	}
?>
