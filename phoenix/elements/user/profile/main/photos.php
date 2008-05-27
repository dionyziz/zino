<?php
	
	function ElementUserProfileMainPhotos( $theuser , $images ) {
		?><ul><?php
			foreach( $images as $image ) {
				?><li><a href="?p=photo&amp;id=<?php
				echo $image->Id;
				?>"><?php
				Element( 'image' , $image , 100 , 100 , '' , $image->Name , $image->Name , '' );
				?></a></li><?php
			}
			if ( $egoalbum->Numphotos > 8 ) {
				?><li><a href="?p=album&amp;id=<?php
				echo $egoalbum->Id;
				?>" class="button" title="Περισσότερες φωτογραφίες">&raquo;</a></li><?php
			}
		?></ul><?php	
	}
?>