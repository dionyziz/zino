<?php

    function ActionImageUpload2( tInteger $albumid , tFile $uploadimage ) {
    	global $libs;
        global $water;
    	global $rabbit_settings;
		global $user;
		
		//$water->DebugThis();
    	$libs->Load( 'image/image' );
		$libs->Load( 'rabbit/helpers/file' );
        if ( !$user->Exists() ) {
            return Redirect();
        }

        $albumid = $albumid->Get();
		if ( $albumid > 0 ) {
			$album = new Album( $albumid );
			if ( $album->IsDeleted() || $album->User->Id != $user->Id ) {
				die( "Not allowed" );
			}
		}
		$extension = File_GetExtension( $uploadimage->Name );
		if ( !( $extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif' ) ) {
			die( "Not supported filetype" );
		}
    	if ( !$uploadimage->Exists() ) {
            return Redirect( '?p=album&id=' . $album->Id );
    	}
        
        header( 'Content-type: text/html' );
        $image = new Image();
		$image->Name = '';
		$setTempFile = $image->LoadFromFile( $uploadimage );
		die( 'breakpoint 1' );
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
        $res = $image->Save();
		die( "Success" );
    	if ( $res < 0 ) {
			?><html><head><title>Upload error</title><script type="text/javascript">
    			alert( 'Παρουσιάστηκε πρόβλημα κατά τη μεταφορά της εικόνας. (<?php
                echo $errornum = $res;
                ?>)' );
    			window.location.href = <?php
    			echo w_json_encode( $rabbit_settings[ 'webaddress' ] . '/?p=uploadframe&albumid=' . $album->Id );
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
    		$album = new Album( $albumid );
    		$size = $image->ProportionalSize( 210 , 210 );
    		$jsimage = array(
    			'id' => $image->Id,
				'userid' => $image->UserId,
    			//'name' => NoExtensionName( $image->Name ),
    			'albumid' => $albumid,
    			'width' => $size[ 0 ],
    			'height' => $size[ 1 ],
    			'imagesnum' => $album->PhotosNum()
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
