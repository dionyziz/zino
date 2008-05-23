<?php

    function ActionImageUpload2( tInteger $albumid , tFile $uploadimage1 , tFile $uploadimage2 , tFile $uploadimage3 ) {
    	global $libs;
        global $water;
    	global $rabbit_settings;
		global $user;
		
		//$water->DebugThis();
    	$libs->Load( 'image/image' );
		$libs->Load( 'rabbit/helpers/file' );
        if ( !$user->Exists() || !$user->HasPermission( PERMISSION_IMAGE_CREATE ) ) {
            return Redirect();
        }

        $albumid = $albumid->Get();
		if ( $albumid > 0 ) {
			$album = New Album( $albumid );
			if ( $album->IsDeleted() || $album->User->Id != $user->Id ) {
				die( "Not allowed" );
			}
		}
		if ( $uploadimage1->Exists() ){
			$uploadimage = $uploadimage1;
		}
		if ( $uploadimage2->Exists() ){
			$uploadimage = $uploadimage2;
		}
		if ( $uploadimage3->Exists() ){
			$uploadimage = $uploadimage3;
		}
		$extension = File_GetExtension( $uploadimage->Name );
		//die( 'extension is ' . strtolower( $extension ) );
		if ( !( strtolower( $extension ) == 'jpg' || strtolower( $extension ) == 'jpeg' || strtolower( $extension ) == 'png' || strtolower( $extension == 'gif' ) ) ) {
			die( "Not supported filetype" );
		}
    	if ( !$uploadimage->Exists() ) {
			if ( $albumid > 0 ) {
				return Redirect( 'index?p=upload&albumid=' . $albumid );
			}
    	}
        
        header( 'Content-type: text/html' );
        $image = New Image();
		$image->Name = '';
		$setTempFile = $image->LoadFromFile( $uploadimage->Tempname );
        switch ( $setTempFile ) {
            case -1: // too big file
                ?><script type="text/javascript">
                    alert( 'H φωτογραφία σου δεν πρέπει να ξεπερνάει το 1MB' );
                    window.location.href = <?php
                    echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=upload&albumid=' . $album->Id );
                    ?>;
                </script><?php
                exit();
            default:
                break;
        }
        $image->Albumid = $albumid;
		die( "trying to save" );
		try {
			$image->Save();
		}
		catch ( ImageException $e ) {
			//some error must have occured
			?><html><head><title>Upload error</title><script type="text/javascript">
				alert( <?php echo $e->getMessage(); ?> );
    			//alert( 'Παρουσιάστηκε πρόβλημα κατά τη μεταφορά της εικόνας' );
    			window.location.href = <?php
    			echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=upload&albumid=' . $album->Id );
    			?>;
    		</script></head><body></body></html><?php
			return;
		}
		die( "brkpnt 1" );
		?><html>
        <head>
        <title>Upload</title>
        </head>
        <body>
        <script type="text/javascript"><?php
    	if ( $albumid != 0 ) {
    		$album = New Album( $albumid );
    		$size = $image->ProportionalSize( 210 , 210 );
    		$jsimage = array(
    			'id' => $image->Id,
				'userid' => $image->Userid,
    			//'name' => NoExtensionName( $image->Name ),
    			'albumid' => $albumid,
    			'width' => $size[ 0 ],
    			'height' => $size[ 1 ],
    			'imagesnum' => $album->Numphotos
    		);
    		?>parent.PhotoList.AddPhoto( <?php
    			echo w_json_encode( $jsimage );
    			?> );<?php
    	} 
		/*
    	else {
    		?>parent.Photos.AddPhotoArticle( <?php
    			echo w_json_encode( $imageid );
    			?> , <?php
				echo w_json_encode( $image->UserId() )
				?>);<?php
    	}
		*/
    	?>
    	window.location.href = <?php
    	echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=upload&albumid=' . $albumid );
    	?>;</script></body></html><?php
    }

?>
