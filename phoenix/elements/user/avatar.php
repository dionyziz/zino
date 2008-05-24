<?php
	
	function ElementUserAvatar( $theuser , $size ) {
		global $rabbit_settings;
		
		//size can either be 150 or 50, which means avatars of size 150x150 or 50x50 respectively
		if ( $theuser->Icon > 0 ) {
			$avatar = New Image( $theuser->Icon );
			Element( 'image' , $avatar , $avatarsize[ 0 ] , $avatarsize[ 1 ] , '' , $theuser->Name , $theuser->Name , '' );
		}
		else {
			?><img src="<?php
			echo $rabbit_settings[ 'imagesurl' ];
			?>anonymous<?php
			echo $size;
			?>.jpg" style="width:<?php
			echo $size;
			?>px;height:<?php
			echo $size;
			?>px;" alt="<?php
			echo $theuser->Name;
			?>" title="<?php
			echo $theuser->Name;
			?>" /><?php
		}
	}
?>
