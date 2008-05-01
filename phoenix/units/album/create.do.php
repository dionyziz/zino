<?php
	
	function UnitAlbumCreate( tString $albumname , tCoalaPointer $albumnode ) {
		global $user;
		
		$albumname = $albumname->Get();
		$albumnode = $albumnode->Get();
		if ( $albumname !== '' ) {
			$album = new Album();
			$album->Name = $albumname;
			$album->Save();
			?>var albumnode = <?php
			echo $albumnode;
			?>;
			$( albumnode ).find( "div.album a" ).attr( "href" , "?p=album&amp;id=<?php
			echo $album->Id;
			?>";<?php
		}
	}
?>
