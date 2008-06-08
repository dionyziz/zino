<?php
	
	function ElementUserAvatar( $theuser , $size , $class = '' , $style = '' , $cssresizable = false , $csswidth = 0 , $cssheight = 0 ) {
		global $rabbit_settings;
		
		// size can either be 150 or 50, which means avatars of size 150x150 or 50x50 respectively
		if ( $theuser->EgoAlbum->Mainimage > 0 ) {
			$avatar = New Image( $theuser->EgoAlbum->Mainimage );
			if ( $size == 150 ) {
				Element( 'image' , $avatar , IMAGE_CROPPED_150x150, $class , $theuser->Name , $theuser->Name , $style , $cssresizable , $csswidth , $cssheight );
			}
			else if ( $size == 100 ) {
				Element( 'image' , $avatar , IMAGE_CROPPED_100x100, $class , $theuser->Name , $theuser->Name , $style , $cssresizable , $csswidth , $cssheight );
			}
		}
		else {
			Element( 'image' , 'anonymous' . $size . '.jpg' , $size . 'x' . $size , $class , $theuser->Name , $theuser->Name , $style , $cssresizable , $csswidth , $cssheight );
		}
	}
?>
