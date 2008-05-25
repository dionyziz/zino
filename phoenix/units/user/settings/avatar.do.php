<?php
	
	function UnitUserSettingsAvatar( tInteger $imageid ) {
		global $user;
		
		$image = New Image( $imageid->Get() );
		?>alert( 'brkpnt1' );<?php
		if ( !$image->IsDeleted() ) {
			if ( $image->User->Id == $user->Id ) {
				$egoalbum = New Album( $user->Egoalbumid );
				$egoalbum->Mainimage = $image->Id;
				$egoalbum->Save();
				?>alert( 'brkpnt2' );
				$( 'div.settings div.tabs form#personalinfo div.option div.setting img.avie' ).attr( {
					src : ExcaliburSettings.photosurl + '<?php
					echo $user->Id;
					?>/<?php
					echo $mage->Id;
					?>?resolution=150x150&sandbox=yes'
				} );<?php
			}
		}
	}
?>
