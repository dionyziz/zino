<?php
    function ActionImageUpload2( tInteger $albumid ) {
    	global $libs;
        global $water;
    	global $rabbit_settings;
		global $user;
		
        if ( !$user->Exists() ) {
            return Redirect();
        }

    	$libs->Load( 'image/image' );
    	$libs->Load( 'albums' );
    	$albumid = $albumid->Get();
        
		$album = new Album( $albumid );

		if ( $album->Exists() && $album->Creator()->Id() != $user->Id() ) {
			die( "You are not allowed to upload to this album! (" . $album->Id() . '/' . $album->DelId() . ')' );
		}

    	if ( !isset( $_FILES['uploadimage']['name'] ) ) {
            return Redirect( '?p=album&id=' . $albumid );
    	}
        
        header( 'Content-type: text/html' );
        
    	$imagename = mystrtolower( basename( $_FILES[ 'uploadimage' ][ 'name' ] ) );
    	$extension = getextension( $imagename );
    	if ( $extension != "jpg" && $extension != "jpeg" && $extension != "gif" && $extension != "png" ) {
    		?><html><head><title>Upload error</title><script type="text/javascript">
    			alert( 'Η φωτογραφία πρέπει να είναι της μορφής .jpg, .gif ή .png' );
    			document.location.href = <?php
    			echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=uploadframe&albumid=' . $albumid );
    			?>;
    		</script></head><body></body></html><?php
    		exit();
    	}
    	if ( substr( $imagename , 0 , strlen( "usericon_" ) ) == "usericon_" ) {
    		die( "upload2.php: Prefix found \"usericon_\"!" );
    	}
    	
    	$tempfile = $_FILES['uploadimage']['tmp_name'];
    	if ( filesize( $tempfile ) > 1024*1024 ) {
    		?><script type="text/javascript">
    			alert( 'H φωτογραφία σου δεν πρέπει να ξεπερνάει το 1MB' );
    			window.location.href = <?php
    			echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=uploadframe&albumid=' . $albumid );
    			?>;
    		</script><?php
    		exit();
    	}
    	
        $extension = getextension( $imagename );
    	$noextname = NoExtensionName( $imagename );
    	if ( $noextname == '' ) {
    		$imagename = 'noname' . rand( 1 , 20 ) . $extension;
    	}
    	$res = submit_photo( $imagename , $tempfile , $albumid , '' );
    	if ( !isset( $res[ 'id' ] ) ) {
			?><html><head><title>Upload error</title><script type="text/javascript">
    			alert( 'Παρουσιάστηκε πρόβλημα κατά τη μεταφορά της εικόνας. (' + <?php
                echo w_json_encode( $res[ 'error' ] );
                ?> + ')' );
    			window.location.href = <?php
    			echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=uploadframe&albumid=' . $albumid );
    			?>;
    		</script></head><body></body></html><?php
		}
		
		Image_Added();
		
		?><html>
        <head>
        <title>Upload</title>
        </head>
        <body>
        <script type="text/javascript"><?php
        $imageid = $res[ 'id' ];
        
		$image = New Image( $imageid );
    	if ( $albumid != 0 ) {
    		$album = New Album( $albumid );
    		$size = $image->ProportionalSize( 210 , 210 );
    		$jsimage = array(
    			'id' => $image->Id(),
				'userid' => $image->UserId(),
    			'name' => NoExtensionName( $image->Name() ),
    			'albumid' => $albumid,
    			'width' => $size[ 0 ],
    			'height' => $size[ 1 ],
    			'imagesnum' => $album->PhotosNum()
    		);
    		?>parent.Photos.AddPhoto( <?php
    			echo w_json_encode( $jsimage );
    			?> );<?php
    	} 
    	else {
    		?>parent.Photos.AddPhotoArticle( <?php
    			echo w_json_encode( $imageid );
    			?> , <?php
				echo w_json_encode( $image->UserId() )
				?>);<?php
    	}
    	?>
    	window.location.href = <?php
    	echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=uploadframe&albumid=' . $albumid );
    	?>;</script></body></html><?php
    }
?>
