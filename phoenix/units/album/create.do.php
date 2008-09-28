<?php
	
	function UnitAlbumCreate( tText $albumname , tCoalaPointer $albumnode ) {
		global $user;
		global $rabbit_settings;
		
		$albumname = $albumname->Get();
		if ( $albumname !== '' ) {
			$album = new Album();
			$album->Name = $albumname;
			$album->Save();
			?>window.location.href = '<?php
			echo $rabbit_settings[ 'webaddress' ];
			?>?p=album&id=<?php
			echo $album->Id;
			?>';<?php
		}
	}
?>
