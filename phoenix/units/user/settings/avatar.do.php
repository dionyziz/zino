<?php
	
	function UnitUserSettingsAvatar( tInteger $imageid ) {
		global $user;
		
		$image = New Image( $imageid->Get() );
		
		if ( !$image->IsDeleted() ) {
			if ( $image->User->Id == $user->Id ) {
				?>$( 'div.settings div.tabs form#personalinfo div.option div.setting img.avie' ).attr( {
					src : ExcaliburSettings.photosurl + '<?php
					echo $user->Id;
					?>/<?php
					echo $image->Id;
					?>/<?php
					echo $image->Id;
					?>_' + ExcaliburSettings.image_cropped_150x150 + '.jpg';
				} );<?php
			}
		}
	}
?>
