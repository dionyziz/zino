<?php
	
	function UnitAlbumCreate( tString $albumname ) {
		global $user;
		
		$albumname = $albumname->Get();
		if ( $albumname !== '' ) {
			?>alert( 'albumname: <?php
			echo $albumname;
			?>' );<?php
			/*
			$album = new Album();
			$album->Name = $albumname;
			$album->Save();
			*/
		}
	}

?>