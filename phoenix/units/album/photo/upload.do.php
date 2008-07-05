<?php
	
	function UnitAlbumPhotoUpload( tInteger $imageid , tCoalaPointer $node ) {
		global $libs;
		global $user;
		global $rabbit_settings;
		
		$libs->Load( 'image/image' );
		
		$image = New Image( $imageid->Get() );
		?>$( <?php
		echo $node;
		?> ).html( <?php
		ob_start();
		Element( 'album/photo/small' , $image , false , true , true );
    	echo w_json_encode( ob_get_clean() );
		?> ).show();<?php
		if ( $image->Album->Numphotos == 1 ) {
			$image->Album->Mainimageid = $image->Id;
			$image->Album->Save();
			if ( $image->Album->Id == $user->Egoalbumid ) {
				if ( $rabbit_settings[ 'production' ] ) {
					?>$( 'div.usersections a img' ).attr( {
						src : ExcaliburSettings.photosurl + '<?php
						echo $user->Id;
						?>/<?php
						echo $image->Id;
						?>/<?php
						echo $image->Id;
						?>' + ExcaliburSettings.image_proportional_210x210 + '.jpg'
					} );<?php
				}
				else {
					?>$( 'div.usersections a img' ).attr( {
						src : ExcaliburSettings.photosurl + '<?php
						echo $user->Id;
						?>/_<?php
						echo $image->Id;
						?>/<?php
						echo $image->Id;
						?>_' + ExcaliburSettings.image_proportional_210x210 + '.jpg'
					} );<?php
				}
			}
		}
		
	}
