<?php
	
	function UnitAlbumPhotoUpload( tInteger $imageid , tCoalaPointer $node ) {
		global $libs;
		
		$libs->Load( 'image' );
		
		$image = New Image( $imageid->Get() );
		?>$( <?php
		echo $node;
		?> ).html( <?php
		ob_start();
		Element( 'album/photo/small' , $image , false , true , true );
    	echo w_json_encode( ob_get_clean() );
		?> ).show();<?php
	}