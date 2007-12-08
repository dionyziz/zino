<?php

    function ActionImageUpload2( tInteger $albumid ) {
    	global $libs;
        global $water;
    	global $rabbit_settings;
		global $user;
		
    	$libs->Load( 'image/image' );

        if ( !$user->Exists() ) {
            return Redirect();
        }

        $albumid = $albumid->Get();

    	if ( !isset( $_FILES['uploadimage']['name'] ) ) {
            return Redirect( '?p=album&id=' . $albumid );
    	}
        
        header( 'Content-type: text/html' );

        $image = new Image();

        $setTempFile = $image->TemporaryFile = $_FILES[ 'uploadimage' ][ 'name' ];
        switch ( $setTempFile ) {
            case -1: // too big file
                ?><script type="text/javascript">
                    alert( 'H φωτογραφία σου δεν πρέπει να ξεπερνάει το 1MB' );
                    window.location.href = <?php
                    echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=uploadframe&albumid=' . $albumid );
                    ?>;
                </script><?php
                exit();
            case -2: // bad extension
                ?><html><head><title>Upload error</title><script type="text/javascript">
                    alert( 'Η φωτογραφία πρέπει να είναι της μορφής .jpg, .gif ή .png' );
                    document.location.href = <?php
                    echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=uploadframe&albumid=' . $albumid );
                    ?>;
                </script></head><body></body></html><?php
                exit();
            default:
                break;
        }

        $setAlbumId  = $image->AlbumId = $albumid;
        if ( !$setAlbumId ) {
			die( "You are not allowed to upload to this album!" );
        }
        
        $res = $image->Submit();

    	if ( $res < 0 ) {
			?><html><head><title>Upload error</title><script type="text/javascript">
    			alert( 'Παρουσιάστηκε πρόβλημα κατά τη μεταφορά της εικόνας. (<?php
                echo $errornum = $res;
                ?>)' );
    			window.location.href = <?php
    			echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=uploadframe&albumid=' . $albumid );
    			?>;
    		</script></head><body></body></html><?php
		}
		
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
				'userid' => $image->UserId,
    			'name' => NoExtensionName( $image->Name ),
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
