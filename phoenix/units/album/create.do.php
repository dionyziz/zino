<?php
	
	function UnitAlbumCreate( tString $albumname , tCoalaPointer $albumnode ) {
		global $user;
		global $rabbit_settings;
		
		$albumname = $albumname->Get();
		if ( $albumname !== '' ) {
			$album = new Album();
			$album->Name = $albumname;
			$album->Save();
			?>windows.location.href = '<?php
			echo $rabbit_settings[ 'webaddress' ];
			?>?p=album&id=<?php
			echo $album->Id;
			?>';<?php
		}
	}
?>
