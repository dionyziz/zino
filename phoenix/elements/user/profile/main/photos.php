<?php
	
	function ElementUserProfileMainPhotos( $theuser ) {
		$egoalbum = New Album( $theuser->Egoalbumid );
		if ( $egoalbum->Numphotos > 0 ) {
			$finder = New ImageFinder();
			$images = $finder->FindByAlbum( $egoalbum , 0 , 10 );
			?><ul><?php
				foreach( $images as $image ) {
					?><li><a href="?p=photo&amp;id=<?php
					echo $image->Id;
					?>"><?php
					Element( 'image' , $image , 100 , 100 , '' , $image->Name , $image->Name , '' );
					?></a></li><?php
				}
				if ( $egoalbum->Numphotos > 10 ) {
					?><li><a href="?p=album&amp;id=<?php
					echo $egoalbum->Id;
					?>" class="button">&raquo;</a></li><?php
				}
			?></ul><?php
		}
	}
?>