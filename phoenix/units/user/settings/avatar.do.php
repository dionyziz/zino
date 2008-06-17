<?php
	
	function UnitUserSettingsAvatar( tInteger $imageid ) {
		global $user;
		
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
                $user->Egoalbum->Mainimage = $image->Id;
                $user->Egoalbum->Save();
			}
            else {
                ?>alert( 'You can\'t use somebody elses images as your avatar' );<?php
            }
		}
        else {
            ?>alert( 'Sorry, this image is deleted' );<?php
        }
	}
?>
