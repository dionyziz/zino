<?php
	
	function UnitAlbumCreate( tString $albumname , tCoalaPointer $albumnode ) {
		global $user;
		
		$albumname = $albumname->Get();
		if ( $albumname !== '' ) {
			$album = new Album();
			$album->Name = $albumname;
			$album->Save();
			?>$( <?php
			echo $albumnode;
			?> ).find( "div.album a" ).attr( "href" , "?p=album&id=<?php
			echo $album->Id;
			?>");<?php
		}
	}
?>
