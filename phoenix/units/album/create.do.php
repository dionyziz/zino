<?php
	
	function UnitAlbumCreate( tString $albumname , tCoalaPointer $albumnode ) {
		global $user;
		
		$albumname = $albumname->Get();
		if ( $albumname !== '' ) {
			?>alert( '<?php echo $albumname; ?>' );<?php
			$album = new Album();
			$album->Name = $albumname;
			$album->Save();
			?>var albumnode = <?php
			echo $albumnode;
			?>;
			$( albumnode ).find( "div.album a" ).attr( "href" , "?p=album&id=<?php
			echo $album->Id;
			?>");<?php
		}
	}
?>
