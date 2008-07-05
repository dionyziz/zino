<?php
	
	function UnitAlbumPhotoMainimage( tInteger $photoid ) {
		global $user;
		global $rabbit_settings;
		$photo = New Image( $photoid->Get() );
		
		if ( $photo->IsDeleted() || $photo->Userid != $user->Id ) {
            return;
        }
        
        $photo->Album->Mainimageid = $photo->Id;
        $photo->Album->Save();

        if ( $photo->Album->Id != $user->Egoalbumid ) {
            return;
        }

        //when photo changes the avatar must be changed
        if ( $rabbit_settings[ 'production' ] ) {
            ?>$( 'div.usersections a img' ).attr( {
                src : ExcaliburSettings.photosurl + '<?php
                echo $user->Id;
                ?>/<?php
                echo $photo->Id;
                ?>/<?php
                echo $photo->Id;
                ?>_' + ExcaliburSettings.image_cropped_150x150 + '.jpg'
            } );<?php
        }
        else {
                ?>$( 'div.usersections a img' ).attr( {
                src : ExcaliburSettings.photosurl + '<?php
                echo $user->Id;
                ?>/_<?php
                echo $photo->Id;
                ?>/<?php
                echo $photo->Id;
                ?>_' + ExcaliburSettings.image_cropped_150x150 + '.jpg'
            } );<?php
        }
    }

?>
