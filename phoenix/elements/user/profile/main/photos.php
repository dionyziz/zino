<?php
	
	function ElementUserProfileMainPhotos( $theuser , $images , $egoalbum ) {
		global $water;
		
		//$die( 'egoalbum numphotos are ' . $egoalbum->Numphotos );
		?><ul><?php
			foreach( $images as $image ) {
				?><li><a href="?p=photo&amp;id=<?php
				echo $image->Id;
				?>"><?php
				Element( 'image/view' , $image , IMAGE_CROPPED_100x100 , '' , $image->Name , $image->Name , '' , false , 0 , 0 );
				?></a></li><?php
			}
			if ( $egoalbum->Numphotos > 8 ) {
				die( 'over 8' );
				?><li><a href="?p=album&amp;id=<?php
				echo $egoalbum->Id;
				?>" class="button" title="Περισσότερες φωτογραφίες">&raquo;</a></li><?php
			}
		?></ul><?php	
	}
?>
