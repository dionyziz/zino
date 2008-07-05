<?php
	
	function ElementUserProfileMainPhotos( $images , $egoalbum ) {
		global $water;
		
		?><ul><?php
			foreach( $images as $image ) {
				?><li><a href="?p=photo&amp;id=<?php
				echo $image->Id;
				?>"><?php
				Element( 'image/view' , $image , IMAGE_CROPPED_100x100 , '' , $image->Name , $image->Name , '' , false , 0 , 0 );
				?></a></li><?php
			}
		?></ul><?php	
	}
?>
