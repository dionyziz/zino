<?php
	
	function UnitUserSettingsAvatar( tInteger $imageid ) {
		global $user;
		
        ?>alert('Saving avatar (<?php
        echo $imageid->Get();
        ?>)');<?php
        return;

		$image = New Image( $imageid->Get() );
		
		if ( !$image->IsDeleted() ) {
			if ( $image->User->Id == $user->Id ) {
				?>$( 'div.settings div.tabs form#personalinfo div.option div.setting img.avie' ).attr( {
					src : ExcaliburSettings.photosurl + '<?php
					echo $user->Id;
					?>/_<?php
					echo $image->Id;
					?>/<?php
					echo $image->Id;
					?>_' + ExcaliburSettings.image_cropped_150x150 + '.jpg'
				} );<?php
			}
		}
	}
?>
